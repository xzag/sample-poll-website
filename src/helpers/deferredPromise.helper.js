class DeferredPromise {
  constructor () {
    this._resolve = null
    this._reject = null

    this._promise = new Promise((resolve, reject) => {
      this._resolve = resolve
      this._reject = reject
    })
  }

  promise () {
    return this._promise
  }

  resolve (data) {
    this._resolve(data)
  }

  reject (err) {
    this._reject(err)
  }
}

export default DeferredPromise
