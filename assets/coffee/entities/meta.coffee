@Wardrobe.module "Entities", (Entities, App, Backbone, Marionette, $, _) ->

  class Entities.MetaItem extends App.Entities.Model
    urlRoot: ->
      App.request("get:url:api") + "/tag"

  class Entities.MetaCollection extends App.Entities.Collection
    model: Entities.MetaItem
    url: ->
      App.request("get:url:api") + "/meta"

  API =
    getAll: (cb) ->
      meta = new Entities.MetaCollection
      meta.fetch
        reset: true
        success: (collection, response, options) ->
          cb meta if cb
        error: ->
          throw new Error("Meta fields not fetched")
      meta

  App.reqres.setHandler "meta:entities", (cb) ->
    API.getAll cb
