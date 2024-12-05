
-- Thêm sản phẩm
INSERT INTO products (id, `name`, category_id, `desc`, guide, price, images, size, color, created_at) VALUES
(1, 'Aviator Sunglasses', 1, 'Classic aviator sunglasses with UV protection', 'Handle with care', 49.99, 'aviator.jpg', 'Medium', 'Gold', NOW()),
(2, 'Wayfarer Sunglasses', 1, 'Stylish wayfarer sunglasses', 'Store in case', 59.99, 'wayfarer.jpg', 'Small', 'Black', NOW()),
(3, 'Blue Light Blocking Glasses', 2, 'Protects your eyes from screen glare', 'Clean with soft cloth', 39.99, 'blue_light.jpg', 'Large', 'Blue', NOW()),
(4, 'Prescription Glasses - Round', 2, 'Round frame prescription glasses', 'Clean lenses regularly', 89.99, 'round_prescription.jpg', 'Medium', 'Silver', NOW()),
(5, 'Sports Sunglasses', 3, 'Durable sunglasses for outdoor sports', 'Do not use chemicals', 69.99, 'sports.jpg', 'Large', 'Red', NOW());

-- Thêm giỏ hàng cho user_id = 1
INSERT INTO carts (id, user_id, created_at, status) VALUES
(1, 1, NOW(), 'active');

-- Thêm sản phẩm vào giỏ hàng với số lượng
INSERT INTO cart_product (cart_id, product_id, quantity) VALUES
(1, 1, 2),  -- 2 cái Aviator Sunglasses
(1, 3, 1),  -- 1 cái Blue Light Blocking Glasses
(1, 5, 3);  -- 3 cái Sports Sunglasses

-- Thêm hóa đơn cho giỏ hàng (giả sử có hóa đơn)
INSERT INTO invoices (id, cart_id, voucher, status, amount) VALUES
(1, 1, NULL, 'pending', 299.95);  -- Tổng tiền tính toán dựa trên các sản phẩm

-- Liên kết sản phẩm với nhiều danh mục (nếu cần)
INSERT INTO category_product (product_id, category_id) VALUES
(1, 1),  -- Aviator Sunglasses thuộc danh mục Sunglasses
(2, 1),  -- Wayfarer Sunglasses thuộc danh mục Sunglasses
(3, 2),  -- Blue Light Blocking Glasses thuộc danh mục Prescription Glasses
(4, 2),  -- Prescription Glasses - Round thuộc danh mục Prescription Glasses
(5, 3);  -- Sports Sunglasses thuộc danh mục Sports Glasses

INSERT INTO promotions (`name`, `description`, discount_percentage, start_date, end_date)
VALUES 
('Black Friday Sale', 'Up to 50% off on selected items', 50, '2024-11-25', '2024-11-30'),
('Holiday Discount', '20% off all products', 20, '2024-12-20', '2025-01-02');

INSERT INTO product_promotion (product_id, promotion_id)
VALUES
(1, 1), -- Sunglasses in Black Friday Sale
(2, 1), -- Reading Glasses in Black Friday Sale
(3, 2); -- Blue Light Glasses in Holiday Discount

INSERT INTO reviews (id, user_id, product_id, review, rating, created_at, updated_at) VALUES
(1, 1, 1, 'Amazing product! Highly recommend.', 5, NOW(), NOW()),
(2, 1, 1, 'Good quality, but a bit expensive.', 4, NOW(), NOW()),
(3, 1, 2, 'Not satisfied with the product.', 2, NOW(), NOW());

UPDATE products
SET rating = COALESCE((
    SELECT AVG(rating)
    FROM reviews
    WHERE reviews.product_id = products.id
), 0)
WHERE id IN (1, 2, 3);