-- Check for duplicate services
SELECT name, category, COUNT(*) as count 
FROM services 
GROUP BY name, category 
HAVING count > 1;

-- Remove duplicates keeping only the first entry
DELETE s1 FROM services s1
INNER JOIN services s2 
WHERE s1.id > s2.id 
AND s1.name = s2.name 
AND s1.category = s2.category;

-- Add unique constraint to prevent future duplicates
ALTER TABLE services 
ADD UNIQUE KEY unique_service (name, category);
