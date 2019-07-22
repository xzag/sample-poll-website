const http = require('http').Server()
const io = require('socket.io')(http)
const path = require('path')
const debug = require('debug')('socket')
const AsyncLock = require('async-lock')
const lock = new AsyncLock()

require('dotenv').config()
const queue = require(path.resolve(process.cwd(), 'socket/services/queue.service'))
queue.init(process.env)

let users = []
let connections = []
let polls = {}

queue.listen({
  'vote': (data) => {
    debug('poll-message', data)
    debug(polls)
    const connected = polls[data.poll_id] || []
    connected.forEach(socketId => {
      io.to(socketId).emit('poll-message', data.poll_id, data)
    })
    debug('emitted to ' + connected.length)
  }
})

io.on('connection', function (socket) {
  debug('user connected with ' + socket.id)

  lock.acquire('connections-lock', () => {
    connections.push(socket.id)
  })

  lock.acquire('users-lock', () => {
    users.push({
      socket_id: socket.id
    })
    debug(users.length + ' users connected')
    io.emit('userConnected', socket.id)
  })

  socket.on('disconnect', function () {
    lock.acquire('connections-lock', () => {
      connections = connections.filter(socketId => {
        return socketId !== socket.id
      })
    })

    lock.acquire('users-lock', () => {
      users = users.filter(item => {
        return item.socket_id !== socket.id
      })
    })

    debug(users.length + ' users left')
  })

  socket.on('enter-poll', function (pollId) {
    debug('enter-poll', pollId)
    lock.acquire('polls-lock', () => {
      polls[pollId] = polls[pollId] || []
      polls[pollId] = polls[pollId].filter(item => {
        return connections.includes(item)
      })
      polls[pollId].push(socket.id)
    })
    io.to(socket.id).emit('poll-entered', pollId)
  })

  socket.on('leave-poll', function (pollId) {
    debug('leave-poll', pollId)
    lock.acquire('polls-lock', () => {
      polls[pollId] = polls[pollId] || []
      polls[pollId] = polls[pollId].filter(socketId => {
        return socketId !== socket.id
      })
    })
  })

  socket.on('poll-message', function (pollId, message) {
    debug('poll-message', [pollId, message])
  })
})

const PORT = process.env.SOCKET_PORT || 3000
http.listen(PORT, function () {
  debug('listening on *:' + PORT)
})
