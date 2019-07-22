import SocketService from "./services/socket.service.js";

class SocketComponent {
  constructor () {
    this.pollId = null
    this.socketService = new SocketService()
    this.socketService.init()
  }

  connect (pollId) {
    this.pollId = pollId
    this.socketService.reconnectedCallback(this.connectToPollSocketCallback)
    this.connectToPollSocket()
  }

  connectToPollSocketCallback () {
    const _this = this
    return () => {
      return _this.connectToPollSocket()
    }
  }

  connectToPollSocket () {
    const _this = this
    this.socketService.enterPoll(this.pollId).then(poll => {
      console.log('entered')
      poll.onMessage(_this.onMessage)
    })
  }

  onMessage (message) {
    console.log('new message', message)
    let event = new CustomEvent('POLL_MESSAGE', {'detail': message})
    window.dispatchEvent(event)
  }
}

export const Component = () => new SocketComponent()
