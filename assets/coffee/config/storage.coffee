class Storage
  constructor: (opts={}) ->
    @key = opts.id or "new"

  getKey: ->
    "post-#{@key}"

  put: (data, ttl = 30000) ->
    # Save it manually so the first load has data.
    $.jStorage.set @getKey(), data, ttl

    # Publish the data so any listeners can update.
    $.jStorage.publish @getKey(), data

  get: (default_val = {}) ->
    $.jStorage.get @getKey(), default_val

  destroy: ->
    $.jStorage.deleteKey @getKey()
