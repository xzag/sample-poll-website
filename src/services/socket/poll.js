class Poll {
  constructor (pollId) {
    this.pollId = pollId
    this.messageHandlers = []
  }

  onMessage (callback) {
    this.messageHandlers.push(callback)
  }

  message (message) {
    this.messageHandlers.forEach(callback => {
      callback(message)
    })
  }
}

export default Poll
