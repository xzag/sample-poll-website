const BeanstalkdWorker = require('beanstalkd-worker')

let client = null
let tube = null

function init (dotenv) {
  client = new BeanstalkdWorker(dotenv.SOCKET_BEANSTALK_HOST || '127.0.0.1', dotenv.SOCKET_BEANSTALK_PORT || 11301)
  tube = dotenv.SOCKET_BEANSTALK_TUBE || 'socket-event-processor'
}

function listen (handlers) {
  client.handle(tube, function (payload) {
    const event = payload.event || 'some-event'
    const data = payload.data || null

    if (handlers.hasOwnProperty(event)) {
      handlers[event](data)
      return Promise.resolve()
    }

    return Promise.reject(new Error('undefined event'))
  })
  client.start()
}

module.exports.init = init
module.exports.listen = listen
