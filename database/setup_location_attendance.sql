-- Location-Based Attendance System Setup
-- Run these queries to set up the location validation feature

-- Step 1: Ensure schools table exists with proper structure
-- (This table should already exist based on your schema)

-- Step 2: Add school_id column to teachers table if it doesn't exist
-- Check if column exists first, then add if needed
ALTER TABLE `teachers` 
ADD COLUMN IF NOT EXISTS `school_id` int(11) DEFAULT NULL;

-- Step 3: Sample data - Insert a school with location
-- Replace with your actual school coordinates
INSERT INTO `schools` (`school_name`, `latitude`, `longitude`, `allowed_radius`) 
VALUES 
('Padmavati School', 18.5204, 73.8567, 100),
('Example School 2', 19.0760, 72.8777, 150);

-- Step 4: Update existing teachers to assign them to a school
-- Replace TEACHER_ID and SCHOOL_ID with actual values
-- Example:
-- UPDATE `teachers` SET `school_id` = 1 WHERE `id` = 1;
-- UPDATE `teachers` SET `school_id` = 1 WHERE `id` = 2;

-- Step 5: Verify the setup
SELECT 
    t.id as teacher_id,
    u.name as teacher_name,
    s.school_name,
    s.latitude as school_lat,
    s.longitude as school_lon,
    s.allowed_radius
FROM teachers t
LEFT JOIN users u ON t.user_id = u.id
LEFT JOIN schools s ON t.school_id = s.id
ORDER BY t.id;

-- Step 6: Test query - Check if a location is within radius
-- This is what the backend does automatically
-- Replace coordinates with test values
SET @teacher_lat = 18.5204;
SET @teacher_lon = 73.8567;
SET @school_id = 1;

SELECT 
    s.school_name,
    s.allowed_radius,
    (
        6371000 * 2 * ASIN(
            SQRT(
                POWER(SIN((RADIANS(@teacher_lat) - RADIANS(s.latitude)) / 2), 2) +
                COS(RADIANS(s.latitude)) * COS(RADIANS(@teacher_lat)) *
                POWER(SIN((RADIANS(@teacher_lon) - RADIANS(s.longitude)) / 2), 2)
            )
        )
    ) as distance_meters,
    CASE 
        WHEN (
            6371000 * 2 * ASIN(
                SQRT(
                    POWER(SIN((RADIANS(@teacher_lat) - RADIANS(s.latitude)) / 2), 2) +
                    COS(RADIANS(s.latitude)) * COS(RADIANS(@teacher_lat)) *
                    POWER(SIN((RADIANS(@teacher_lon) - RADIANS(s.longitude)) / 2), 2)
                )
            )
        ) <= s.allowed_radius 
        THEN 'ALLOWED ✓' 
        ELSE 'DENIED ✗' 
    END as attendance_status
FROM schools s
WHERE s.id = @school_id;

-- Notes:
-- 1. Get your school's GPS coordinates from Google Maps
-- 2. allowed_radius is in meters (100 = 100 meters)
-- 3. Make sure all teachers have a school_id assigned
-- 4. Teachers without school_id can still mark attendance (no location check)
