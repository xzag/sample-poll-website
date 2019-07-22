import io from 'socket.io-client'
import DeferredPromise from './../helpers/deferredPromise.helper'
import Poll from './socket/poll'

let _pollConnections = []
let _polls = {}
let _reconnectCallbacks = []
let _disconnectedCallbacks = []
let _notificationCallbacks = []

class SocketService {
  constructor () {
  }

  init () {
    console.log('connecting')
    const socket = io(location.host + ':3000', {

    })

    socket.on('connect', SocketService.onConnect)
    socket.on('disconnect', SocketService.onDisconnect)
    socket.on('poll-entered', SocketService.onPollEntered)
    socket.on('poll-message', SocketService.onPollMessage)
    socket.on('notification', SocketService.onNotification)

    this._socket = socket
  }

  disconnectedCallback (cb) {
    _disconnectedCallbacks.push(cb)
  }

  reconnectedCallback (cb) {
    _reconnectCallbacks.push(cb)
  }

  notificationCallback (cb) {
    _notificationCallbacks.push(cb)
  }

  static onConnect () {
    _reconnectCallbacks.forEach(cb => {
      cb()
    })
  }

  static onDisconnect () {
    _disconnectedCallbacks.forEach(cb => {
      cb()
    })
  }

  enterPoll (pollId) {
    this._socket.emit('enter-poll', pollId)

    _pollConnections[pollId] = new DeferredPromise()
    return _pollConnections[pollId].promise()
  }

  leavePoll (pollId) {
    delete _pollConnections[pollId]
    delete _polls[pollId]
    this._socket.emit('leave-poll', pollId)
  }

  static onPollEntered (pollId) {
    console.log(_pollConnections[pollId])
    _polls[pollId] = new Poll(pollId)
    _pollConnections[pollId].resolve(_polls[pollId])
  }

  static onPollMessage (pollId, message) {
    _polls[pollId].message(message)
  }

  static onNotification (notification) {
    _notificationCallbacks.forEach(cb => {
      cb(notification)
    })
  }
}

export default SocketService
