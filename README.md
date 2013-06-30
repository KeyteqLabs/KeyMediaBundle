KeyMediaBundle
==============

Symfony/eZ5 bundle for KeyMedia

## Dependencies
* eZ Publish 5.x
* <a href="http://github.com/KeyteqLabs/keymedia-extension/">The KeyMedia legacy extension</a>

And if you want a better user experience;
* <a href="http://github.com/KeyteqLabs/ezexceed/">eZ Exceed</a>

## Activation
In your global `parameters.yml`, define which siteaccess or siteaccess group KeyMedia should be activated for. Make sure you don’t activate it for your admin (back office) siteaccess.

The cleanest way to do this is probably to define a separate group for the frontend siteaccess:

```yaml
ezpublish:
    siteaccess:
        default_siteaccess: mysite
        list:
            - mysite
            - eng
            - nor
            - mysite_admin
        groups:
            common_group:
                - mysite
                - eng
                - nor
                - mysite_admin
            frontend_group:
                - mysite
                - eng
                - nor
```

The settings `parameters.keymedia_active_siteaccesses` should then be added to `parameters.yml` like this:

```yaml
parameters:
    keymedia_active_siteaccesses: frontend_group
```

## Usage
It’s quite similar to the legacy way of doing things – `attribute_view_gui` has become `ez_render_field`, and the syntax is slightly different due to the switch to Twig:
```jinja
{{ ez_render_field(content, 'my_keymedia_field_identifier', {
    format: 'My-named-downscale',
    quality: 90,
    title: 'My descriptive alternative text'
}) }}
```

If you don’t want to use any of the named downscales, you can provide an array with width and height values just as before;

```jinja
{{ ez_render_field(content, 'my_keymedia_field_identifier', {
    format: [800, 600],
    ...
}) }}
```
