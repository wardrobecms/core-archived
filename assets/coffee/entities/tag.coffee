@Wardrobe.module "Entities", (Entities, App, Backbone, Marionette, $, _) ->

  class Entities.Tag extends App.Entities.Model
    urlRoot: ->
      App.request("get:base:url") + "/tag"

  class Entities.TagCollection extends App.Entities.Collection
    model: Entities.Tag
    url: ->
      App.request("get:base:url") + "/tag"

  API =
    getAll: (cb) ->
      tags = new Entities.TagCollection
      tags.fetch
        reset: true
        success: (collection, response, options) ->
          cb tags if cb
        error: ->
          throw new Error("Tags not fetched")
      tags

  App.reqres.setHandler "tag:entities", (cb) ->
    API.getAll cb
