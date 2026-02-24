-- Add first_order_discount column to track if user has used their first order discount
ALTER TABLE users ADD COLUMN IF NOT EXISTS first_order_discount TINYINT(1) DEFAULT 1;

-- Set existing users to 0 (already used discount)
UPDATE users SET first_order_discount = 0 WHERE id > 0;
