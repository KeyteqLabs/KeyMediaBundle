KeyMediaBundle
==============

Symfony/eZ5 bundle for KeyMedia

## Dependencies
* eZ Publish 5.x
* <a href="http://github.com/KeyteqLabs/keymedia-extension/">The KeyMedia legacy extension</a>

And if you want a better user experience;
* <a href="http://github.com/KeyteqLabs/ezexceed/">eZ Exceed</a>

## Usage
It’s quite similar to the legacy way of doing things – `attribute_view_gui` has become `ez_render_field`, and the syntax is slightly different due to the switch to Twig:
```twig
{{ ez_render_field(content, 'my_keymedia_field_identifier', {
    format: 'My-named-downscale',
    quality: 90,
    title: 'My descriptive alternative text'
}) }}
```

If you don’t want to use any of the named downscales, you can provide an array with width and height values just as before;

```twig
{{ ez_render_field(content, 'my_keymedia_field_identifier', {
    format: [800, 600],
    ...
}) }}
```
