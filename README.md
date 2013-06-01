# Support.js

The aim of this project is to create a feedback tab that doesn't force users to type in details that the owner of the site knows about them already: their name, email address etc.

It also allows to automatically send extra data like browser and operating system, current page and any additional info.


## How to install it?

```
<script src="https://s3-eu-west-1.amazonaws.com/supportjs/support.js"></script>
<script>
    supportjs.user('John Doe', 'john@johndoe.com');
    supportjs.variables({
        'company_name' : 'JohnDoeLtd',
        'plan' : 'Pro',
        'user_id' : 123
    });
    supportjs.load('api-key-goes-here');
</script>
```
