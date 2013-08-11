# Mixin Helpers
#--------------
_.mixin
  # Removes trailing slashes on Urls
  stripTrailingSlash : (url) ->
    return if url.slice(-1) is '/' then url.substr(0, url.length - 1) else url