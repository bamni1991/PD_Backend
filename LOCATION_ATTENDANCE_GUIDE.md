# Location-Based Teacher Attendance System

## Overview
This implementation adds location validation to the teacher attendance system. Teachers can only mark attendance when they are within the allowed radius of their school.

## Database Schema

### Schools Table
```sql
CREATE TABLE `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `allowed_radius` int(11) DEFAULT 100,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Teachers Table (Updated)
```sql
CREATE TABLE `teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `academic_session_id` int(11) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `joining_date` date NOT NULL,
  `experience_years` int(11) DEFAULT 0,
  `gender` enum('male','female') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `aadhar_number` varchar(12) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `aadhar_copy` varchar(255) DEFAULT NULL,
  `qualification_certificate` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `school_id` int(11) DEFAULT NULL,  -- NEW FIELD
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Backend Implementation

### 1. School Model (`app/Modules/School/Models/School.php`)
Created a new School model with:
- **Haversine Formula**: Calculates distance between two GPS coordinates
- **isWithinRadius()**: Checks if teacher's location is within allowed radius
- **calculateDistance()**: Returns distance in meters between two points

### 2. Teacher Model Updates (`app/Modules/School/Models/Teacher.php`)
- Added `school_id` to fillable fields
- Added `school()` relationship to School model

### 3. TeacherController Updates (`app/Modules/School/Controllers/TeacherController.php`)
Updated `markAttendance()` method to:
- Load teacher with school relationship
- Validate teacher's GPS coordinates against school location
- Return detailed error if outside allowed radius
- Include both English and Marathi error messages

**Error Response Format:**
```json
{
  "status": false,
  "message": "You are outside the allowed radius for marking attendance",
  "error_code": "LOCATION_OUT_OF_RANGE",
  "details": {
    "school_name": "School Name",
    "allowed_radius": 100,
    "current_distance": 250.45,
    "message_marathi": "तुम्ही शाळेच्या परवानगी असलेल्या क्षेत्राबाहेर आहात. कृपया शाळेच्या परिसरात या."
  }
}
```

## Frontend Implementation

### TeacherMarkAttendance.js Updates
Enhanced error handling in `submitToServer()` function:
- Detects `LOCATION_OUT_OF_RANGE` error code
- Displays user-friendly alert with:
  - School name
  - Allowed radius
  - Current distance from school
  - Bilingual message (English + Marathi)

## How It Works

1. **Teacher Marks Attendance**:
   - App requests GPS location with high accuracy
   - Sends latitude/longitude to backend

2. **Backend Validation**:
   - Retrieves teacher's school information
   - Calculates distance using Haversine formula
   - Compares distance with `allowed_radius`

3. **If Within Radius**:
   - Attendance is marked successfully
   - Coordinates saved in database

4. **If Outside Radius**:
   - Returns 403 error with details
   - Frontend shows alert with distance information
   - Attendance is NOT marked

## Configuration

### Setting Up Schools
You need to add school data to the `schools` table:

```sql
INSERT INTO schools (school_name, latitude, longitude, allowed_radius) 
VALUES ('Your School Name', 18.5204, 73.8567, 100);
```

### Assigning Teachers to Schools
Update existing teachers:

```sql
UPDATE teachers SET school_id = 1 WHERE id = YOUR_TEACHER_ID;
```

Or include `school_id` when creating new teachers.

## Testing

### Test Scenario 1: Within Radius
- Teacher location: 18.5204, 73.8567
- School location: 18.5204, 73.8567
- Distance: ~0 meters
- Result: ✅ Attendance marked

### Test Scenario 2: Outside Radius
- Teacher location: 18.5304, 73.8667
- School location: 18.5204, 73.8567
- Distance: ~1500 meters
- Allowed radius: 100 meters
- Result: ❌ Error message displayed

## Important Notes

1. **GPS Accuracy**: The app requests high-accuracy GPS (BestForNavigation)
2. **Default Radius**: 100 meters (configurable per school)
3. **Haversine Formula**: Accurate for distances up to a few kilometers
4. **Error Handling**: Graceful fallback if school has no coordinates
5. **Admin Override**: Location check only applies when coordinates are available

## Future Enhancements

- [ ] Admin panel to manage school locations
- [ ] Visual map showing allowed radius
- [ ] Attendance history with location markers
- [ ] Geofencing notifications
- [ ] Multiple school campus support

## Troubleshooting

### Connection Errors (404 or Network Error)
If you get a 404 or connection error when marking attendance:

1. **Check Backend URL**: Ensure `app.config.js` has the correct IP address of your machine.
   - Run `ipconfig` in terminal to get your current IP.
   - Update `backendUrl` in `app.config.js` if your IP has changed.
   - **XAMPP Users**: Ensure your URL includes `/public/`.
   - **Check Path**: If your project is in a subfolder with spaces (e.g., `Schoool dev`), ensure the URL matches: `http://IP/Schoool%20dev/Pd_backend/public/api/school/`.
   - **IMPORTANT**: After changing `app.config.js`, restart Expo with cache clear:
     ```bash
     npx expo start -c
     ```

2. **Check Server Status**: Ensure XAMPP (Apache & MySQL) is running.

3. **Check Route**: Verification script is available at `Pd_backend/list_routes.bat`.

