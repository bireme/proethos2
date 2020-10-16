How to delegate user roles on the first access using Oauth2 authentication (Azure AD)
=====================================================================================

If is your first access using Oauth2 authentication (Azure AD), is required to create the admin user and delegate their roles running the command `proethos2:delegate-user-roles` with the following arguments:

- `email:` User email
- `roles:` User roles ID (comma-separated): 1-investigator|2-secretary|3-member-of-committee|4-member-ad-hoc|5-administrator

Usage:

```
$ php app/console proethos2:delegate-user-roles <email> <roles>
```

Example:

```
$ php app/console proethos2:delegate-user-roles admin@proethos2.com 1,2,3,4,5
```
