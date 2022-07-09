# Idea-Inker
# Getting started
### Install Vendor
Make a composer install

    composer install
### Database
Create the database with

    php bin/console d:d:c
or
    
    symfony console d:d:c
push the database schema

    php bin/console d:s:u --force
or

    symfony console d:s:u --force
### Generate JWT certificate
    
    bin/console lexik:jwt:generate-keypair

# Api

### post

```json
{
  "post_id": "",
  "content": "",
  "created_at": "",
  "updated_at": "",
  "deleted_at": "",
  "image": [
    {
      "image_file": "base64"
    }
  ],
  "created_by": "id_user"
}
```
### Notice
```json
{
  "notice_id": "",
  "stars": "",
  "comment": "",
  "user_noted": "",
  "user_noting": ""
}
```
### Salon
```json
{
  "salon_id": "",
  "name": "",
  "address": "",
  "zip_code": "",
  "city": "",
  "manager": "",
  "salon_image": "",
  "created_at": "",
  "updated_at": "",
  "deleted_at": "",
  "latitude": "",
  "longitude": "",
  "artists": [],
  "notices": []
}
```

### Routes
````
/api/post/
/api/salon/
/{id}/add/artist/{artistId} :methods PATH
/{id}/add/artists/{artistId} :methods PATH
/{id}/remove/artist/{artistId} :methods PATH
/{id}/remove/artists/{artistId} :methods PATH
/api/notice/
A modifier selon le get le post ect 
