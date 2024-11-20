# Laravel API Documentation

## Introduction

An API built with Laravel, providing features for product management, categories, promotions, cart operations, payments, and user authentication.

## Features

-   **Product Management**: Create, update, delete, and retrieve products
-   **Category Management**: Associate and filter products by categories
-   **Promotions**: Manage and retrieve promotional offers
-   **Cart Operations**: Add, update, and manage products in cart
-   **Payment Integration**: Momo payment gateway support
-   **User Authentication**: Registration, login, token management

## Requirements

-   PHP >= 8.0
-   Composer
-   Laravel >= 10
-   MySQL
-   Postman

## Installation

1. Clone repository:

```bash
git clone https://github.com/LierVelom/api.git
cd api
```

2. Install dependencies:

```bash
composer install
```

3. Configure `.env`:

```bash
cp .env.example .env
```

4. Run migrations:

```bash
php artisan migrate --seed
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Start server:

```bash
php artisan serve
```

## API Endpoints

### Authentication

-   **Register**: `POST /api/register`
-   **Login**: `POST /api/login`
-   **Logout**: `POST /api/logout`

### Products

-   **List Products**: `GET /api/products`
-   **Product Details**: `GET /api/products/{id}`
-   **Related Products**: `GET /api/products/{id}/related`

### Product Filter

-   **Filter Products**:  
    `GET /api/products`  
    Query Parameters:

    -   `category_id[eq]`: Filter by exact category ID
    -   `price[gt]`: Filter products with price greater than the given value
    -   `price[lt]`: Filter products with price less than the given value
    -   ...
        Supported Parameters: `eq`, `gt`, `lt`, `ne`, `gte`, `lte`

    Example Request:

    ```
    GET /api/products?category_id[eq]=1&price[gt]=100&price[lt]=500
    ```

    ```json
    {
        "data": [
            {
                "id": 2,
                "name": "Wayfarer Sunglasses",
                "description": "Stylish wayfarer sunglasses",
                "price": 59.99,
                "size": "Small",
                "color": "Black",
                "created_at": "2024-11-14T15:55:42.000000Z",
                "promotions": [
                    {
                        "id": 1,
                        "name": "Black Friday Sale",
                        "description": "Up to 50% off on selected items",
                        "discount_percentage": "50.00",
                        "discount_amount": "50.00",
                        "start_date": "2024-11-25",
                        "end_date": "2024-11-30"
                    }
                ]
            }
        ]
    }
    ```

### Cart

-   **View Cart**: `GET /api/cart`
-   **Add Product**: `POST /api/cart/add`

### Payments

-   **Momo Checkout**: `POST /api/checkout`

## Testing

-   Use Postman
-   Import `postman_collection.json`

## Contributing

Contributions welcome via pull requests.

## License

MIT License
