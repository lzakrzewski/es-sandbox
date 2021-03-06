# Shopping simulation - `CLI` entry point

```
bin/console simulate-shopping
```

This `CLI` writes events to `EventStore`. Events are emitting by [Basket](domain-model.md#aggregate) aggregate.

Arguments:
- `limit` (optional) limit of products in basket. Default 5
- `basketId` (optional) id of basket. It can be manually provided and it should be valid `uuid4` string. See [Online UUID Generator](https://www.uuidgenerator.net/)

## EventStore.UI

`basketId` is also name of `stream` in `EventStore`. 
You can view this stream using `EventStore.UI`. Default auth is `admin:changeit`.
Example: 
If basket id is `91e65f32-4fd6-4527-8995-8d76fbbe52a0` which is also name of stream and you configured `EventStore` on `http://127.0.0.1:2113` 
then you should be able to view your stream on [http://127.0.0.1:2113/web/index.html#/streams/91e65f32-4fd6-4527-8995-8d76fbbe52a0](http://127.0.0.1:2113/web/index.html#/streams/91e65f32-4fd6-4527-8995-8d76fbbe52a0)

More about writing streams: [http://docs.geteventstore.com/http-api/3.6.0/writing-to-a-stream/](http://docs.geteventstore.com/http-api/3.6.0/writing-to-a-stream/)

## Example
![](simulate-shopping1.png)