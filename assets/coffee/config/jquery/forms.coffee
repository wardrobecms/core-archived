# Fill the form with the JSON
$.fn.fillJSON = (json) ->
  $el = $(this)
  for key, val of json
    if key isnt "active"
      $el.find("[name='#{key}']").val(val)

$.fn.showAlert = (title, msg, type) ->
  $el = $(this)
  html = "<div class='alert alert-block alert-dismissable #{type}'>
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
    <strong>#{title}</strong> #{msg}
  </div>"
  $el.html(html).fadeIn()
  # $(".alert").delay(3000).fadeOut 400
