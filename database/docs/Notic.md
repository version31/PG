# Notice

## variables

+ {{url}}: https://dev.parsiangram.com
+ {{v1}}: https://dev.parsiangram.com/api/v1
+ {{v2}}: https://dev.parsiangram.com/api/v2


# Version 2.0

## structures

There is no need for ```status``` . Please use HTTP status code to check a response In this version.

#### error example:


+ Response 4xx (application/json)


    {
        "errors": {
            "title": [
                "فیلد عنوان الزامی است."
            ]
        },
    }

#### success example:

```message``` may not exist in all paths.

+ Response 2xx (application/json)

        {
            "data": {
                "id": 1
            },
            "message": "کاربر مورد نظر با موفقیت فالو شد"
        }
