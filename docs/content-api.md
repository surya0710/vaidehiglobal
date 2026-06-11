# Content Management API

Protected APIs for creating and modifying categories and blogs.

## Authorization

Send this token with every request:

```http
Authorization: Bearer vaidehi-global-api-token
Accept: application/json
```

Alternative token header:

```http
X-API-TOKEN: vaidehi-global-api-token
Accept: application/json
```

## Endpoints

| Action | Method | Endpoint |
| --- | --- | --- |
| Create category | POST | `/api/categories/create` |
| Modify category | PUT | `/api/categories/{category_id}/modify` |
| Create blog | POST | `/api/blogs/create` |
| Modify blog | PUT or POST | `/api/blogs/{blog_id}/modify` |

## Category APIs

Category APIs do not require an image in this document. Use JSON body in Postman.

### Create Category

`POST /api/categories/create`

Headers:

```http
Authorization: Bearer vaidehi-global-api-token
Accept: application/json
Content-Type: application/json
```

Body:

```json
{
  "name": "Steel Products",
  "slug": "steel-products",
  "parent_id": null,
  "status": "1",
  "meta_title": "Steel Products | Vaidehi Global",
  "meta_keywords": "steel products, industrial steel, vaidehi global",
  "meta_description": "Explore high quality steel products from Vaidehi Global."
}
```

### Modify Category

`PUT /api/categories/{category_id}/modify`

Headers:

```http
Authorization: Bearer vaidehi-global-api-token
Accept: application/json
Content-Type: application/json
```

Body:

```json
{
  "name": "Updated Steel Products",
  "slug": "updated-steel-products",
  "parent_id": null,
  "status": "1",
  "meta_title": "Updated Steel Products | Vaidehi Global",
  "meta_keywords": "updated steel products, industrial steel",
  "meta_description": "Updated category description for steel products."
}
```

## Blog Featured Image

Blog create requires a featured image. In Postman, use `Body -> form-data`, not raw JSON.

For blog category selection, send `category_slug`. The API will find the category and save its `category_id` automatically. You can still send `category_id` instead if you already have the id.

Required file field:

```text
featured_image = selected image file
```

The API also accepts `image` as the file field for backward compatibility, but `featured_image` is recommended.

Allowed image types:

```text
jpg, jpeg, png, webp
```

Maximum image size:

```text
2048 KB
```

## Blog APIs

### Create Blog

`POST /api/blogs/create`

Headers:

```http
Authorization: Bearer vaidehi-global-api-token
Accept: application/json
```

Postman body type:

```text
form-data
```

Fields:

```text
category_slug = steel-products
title = How to Choose the Right Steel Product
slug = how-to-choose-the-right-steel-product
content = <p>Choosing the right steel product depends on application, grade, strength, finish, and durability requirements.</p>
status = 1
meta_title = How to Choose the Right Steel Product
meta_keywords = steel product guide, steel selection, industrial materials
meta_description = A quick guide to choosing the right steel product for your industrial needs.
read_time = 5
featured_image = select-file
```

Important: Set `featured_image` type to `File` in Postman.

### Modify Blog Without Changing Image

`PUT /api/blogs/{blog_id}/modify`

Headers:

```http
Authorization: Bearer vaidehi-global-api-token
Accept: application/json
Content-Type: application/json
```

Body:

```json
{
  "category_slug": "steel-products",
  "title": "Updated Guide to Choosing Steel Products",
  "slug": "updated-guide-to-choosing-steel-products",
  "content": "<p>This updated guide explains how to compare steel products based on strength, corrosion resistance, grade, and intended use.</p>",
  "status": true,
  "meta_title": "Updated Guide to Choosing Steel Products",
  "meta_keywords": "steel guide, steel products, industrial material selection",
  "meta_description": "Updated guide for selecting steel products for industrial applications.",
  "read_time": 7
}
```

### Modify Blog And Replace Image

Use `POST` for multipart image replacement:

`POST /api/blogs/{blog_id}/modify`

Headers:

```http
Authorization: Bearer vaidehi-global-api-token
Accept: application/json
```

Postman body type:

```text
form-data
```

Fields:

```text
category_slug = steel-products
title = Updated Guide to Choosing Steel Products
slug = updated-guide-to-choosing-steel-products
content = <p>This updated guide explains how to compare steel products based on strength, corrosion resistance, grade, and intended use.</p>
status = 1
meta_title = Updated Guide to Choosing Steel Products
meta_keywords = steel guide, steel products, industrial material selection
meta_description = Updated guide for selecting steel products for industrial applications.
read_time = 7
featured_image = select-file
```

Important: Set `featured_image` type to `File` in Postman.

## Success Responses

Create APIs return `201 Created`.

Modify APIs return `200 OK`.

Example blog create response:

```json
{
  "message": "Blog created successfully.",
  "data": {
    "id": 1,
    "category_id": 1,
    "title": "How to Choose the Right Steel Product",
    "slug": "how-to-choose-the-right-steel-product",
    "image": "1780000000_a1b2c3d4.png",
    "read_time": 5
  }
}
```

## Error Responses

Unauthorized:

```json
{
  "message": "Unauthorized."
}
```

Missing blog image:

```json
{
  "message": "The featured image field is required when image is not present.",
  "errors": {
    "featured_image": [
      "The featured image field is required when image is not present."
    ]
  }
}
```

Validation error:

```json
{
  "message": "The slug has already been taken.",
  "errors": {
    "slug": [
      "The slug has already been taken."
    ]
  }
}
```
