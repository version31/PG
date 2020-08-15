### {{v2}}/auth/login/password [POST]

+ Request (application/json)

        {
            "mobile": "09185257989",
            "password": "123"
        }



        
+ Response 200 (application/json)

        {
            "data": {
                "id": 977,
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImZjMjcwNmZiZDQ5ZDE3MzZiOGU2YWZiNTVhNmY1Mzg4MWJjZWYyZTY3OTk4YzlmMjMzODE2NDEzMmI2Y2MwMDVlNjM0MjNiM2RiNGJlNGU1In0.eyJhdWQiOiIxIiwianRpIjoiZmMyNzA2ZmJkNDlkMTczNmI4ZTZhZmI1NWE2ZjUzODgxYmNlZjJlNjc5OThjOWYyMzM4MTY0MTMyYjZjYzAwNWU2MzQyM2IzZGI0YmU0ZTUiLCJpYXQiOjE1OTY5OTU4MjgsIm5iZiI6MTU5Njk5NTgyOCwiZXhwIjoxNjI4NTMxODI4LCJzdWIiOiI5NzciLCJzY29wZXMiOltdfQ.jkSRryht--WuIoySD-j1-vJ6VFEJ_VE6awXQfe4YA5JcteKsb2AsdyCJ4r0giMz5vCxxem8G-LkDjXGHb3ScU3Q_wqAP1QLeRRmy9aJzla6dCf5AnsfRhMCzTqb_YHUeAte05ERGaSRTyHqUgsFFl0rABkL41DaU1A5-J8SbBXZ1KeXu9ki47_gWU43wRp0F52111qZv793m4sgyv_M2nuKO_VeTrRRrrJukb6pTIQbBI5gvSY-6rOHqZE-k28DwgUYRVcQ7y2-qxqdNbhVdgx6DoNCJawoPuxXww2zs_BF_KdLRo0StsMR2ZfBt6D8wJZ27bKqMqRgZa7LL_6sBKEc1dkSzic40Pa4K1_0WADVTjax-5tssGKlJ2Gtg1vrhMIkXEiLfmCTKHSFPxf-_QetvNaaT2yQ_-7_v_kMoDk7646XNfwgpLV4zUXZ9BeC6mIgF4Dp9xKde-z6szv8vgAxhhTs41PTeWbMVoDW6gEADBgp8t1kER-eYzGXR3oJSIL1rWaFYZ3fxRocQmKZlEo--2ZA160fwjwzTyEiTS2GXQsqDWDKX02v1VOmQTjM1C4IuJemz9B_O0-Kw_2dExg4zV93wUD7oNFRsLxd_xhAcacdKO3H_r0SaA9-pG8I5WIpfPsdbtO1R-9SsxRvT6oc3YSyOXuuF0aE5B3FC4FE",
                "user_registered": true,
                "status": 1,
                "mobile": "09185257989",
                "roles": [
                    "user"
                ]
            }
        }

+ Response 401 (application/json)

        {
            "data": [],
            "errors": {
                "unauthorised": [
                    "اطلاعات وارد شده صحیح نمی باشد"
                ]
            }
        }
