@Wardrobe.module "PostApp.Edit", (Edit, App, Backbone, Marionette, $, _) ->

  class Edit.Post extends App.Views.PostView

    onRender: ->
      @fillJSON()
      @_setDate()
      @_setActive()
      @_setTags()

    _setDate: ->
      publishDate = @model.get("publish_date")
      if _.isObject publishDate
        publishDate = publishDate.date
      date = moment.utc(publishDate, "YYYY-MM-DDTHH:mm:ss")
      @$(".js-date").val date.format "MMM Do YYYY, hh:mma"
      @$("#publish_date").val date.format("MMM Do, YYYY h:mm A")

    _setActive: ->
      if parseInt(@model.get("active")) is 1
        @$(".publish").text Lang.post_publish
        @$('input:radio[name="active"]').filter('[value="1"]').attr('checked', true);
      else
        @$(".publish").text Lang.post_save
        @$('input:radio[name="active"]').filter('[value="0"]').attr('checked', true);

    _setTags: ->
      tags = _.pluck(@model.get("tags"), "tag")
      @$("#js-tags").val tags