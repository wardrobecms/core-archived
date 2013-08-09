@Wardrobe.module "PostApp.Meta", (Meta, App, Backbone, Marionette, $, _) ->

  class Meta.ItemView extends App.Views.ItemView
    className: "field"
    template: "post/meta/templates/item"

    onShow: ->
      @fillForm()
      @setUpTags()
      @$("textarea.js-value").autosize(classname: "expand").focus()

    fillForm: ->
      @$(".js-key").val @model.get("key")
      @$(".js-value").val @model.get("value")

    # Setup the tags instance
    setUpTags: (tags) ->
      @$(".js-key").selectize
        create: true
        sortField: 'text'

  class Meta.Grid extends App.Views.CompositeView
    className: "extra-fields"
    template: "post/meta/templates/grid"
    itemView: Meta.ItemView
    itemViewContainer: ".fields"

    events:
      "click .js-add-field" : "addField"

    initialize: ->
      console.log @collection.length

    addField: (e) ->
      e.preventDefault()
      @collection.add
        key: ""
        value: ""