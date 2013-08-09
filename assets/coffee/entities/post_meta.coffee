@Wardrobe.module "Entities", (Entities, App, Backbone, Marionette, $, _) ->

  class Entities.Meta extends App.Entities.Model

  class Entities.MetaCollection extends App.Entities.Collection
    model: Entities.Meta

  API =
    setAll: (meta) ->
      new Entities.MetaCollection meta

  App.reqres.setHandler "set:all:meta", (meta) ->
    meta = [key: "", value: ""] if not meta
    API.setAll meta