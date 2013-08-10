@Wardrobe.module "PostApp.Meta", (Meta, App, Backbone, Marionette, $, _) ->

  class Meta.ItemView extends App.Views.ItemView
    className: "field"
    template: "post/meta/templates/item"

    initialize: (opts) ->
      @meta = opts.allMeta

    templateHelpers: ->
      # generate a unique id for the meta array
      getCid: =>
        return @cid

    onShow: ->
      @fillForm()
      @setUpTags()
      @setupMeta()
      @$("textarea.js-value").autosize(classname: "expand").focus()

    fillForm: ->
      @$(".js-key").val @model.get("key")
      @$(".js-value").val @model.get("value")

    # Setup the tags instance
    setUpTags: (tags) ->
      @$(".js-key").selectize
        create: true
        sortField: 'text'

    setupMeta: ->
      return @ if @meta.length is 0
      @meta.each (item) =>
        @$("optgroup").append $("<option>",
          value: item.get("key")
          text: item.get("key")
        )

  class Meta.Grid extends App.Views.CompositeView
    className: "extra-fields"
    template: "post/meta/templates/grid"
    itemView: Meta.ItemView
    itemViewContainer: ".fields"

    initialize: (opts) ->
      @meta = opts.meta

    itemViewOptions: ->
      allMeta: @meta

    events:
      "click .js-add-field" : "addField"

    addField: (e) ->
      e.preventDefault()
      @collection.add
        key: ""
        value: ""