# School API Routes - Complete Test List

## Base URL
```
http://localhost/Python_my/api/school
```

---

## 🔐 Authentication Routes

### 1. Login
```http
POST /api/school/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

### 2. Upload Profile Image
```http
POST /api/school/upload-profile-image
Content-Type: multipart/form-data

{
  "image": <file>
}
```

---

## 👨‍🎓 Student Routes

### 3. Get Student Creating Data
```http
GET /api/school/students/getStudentFromCreatingData
```

### 4. Register Student
```http
POST /api/school/students/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "class_id": 1,
  "academic_session_id": 1
  // Add other required fields
}
```

### 5. Fetch Student Admin Screen Data
```http
GET /api/school/students/feachStudentAdminScreenData
```

### 6. Get Student By ID
```http
GET /api/school/students/{studentId}

Example: GET /api/school/students/1
```

### 7. Get Student By ID (Alternative Route)
```http
GET /api/school/student/{studentId}

Example: GET /api/school/student/1
```

### 8. Update Student
```http
POST /api/school/students/update/{studentId}
Content-Type: application/json

Example: POST /api/school/students/update/1

{
  "name": "John Updated",
  "email": "john.updated@example.com"
  // Add other fields to update
}
```

### 9. Get User Fees History
```http
GET /api/school/students/getUserFeesHistoryData/{studentId}

Example: GET /api/school/students/getUserFeesHistoryData/1
```

### 10. Update Fees
```http
POST /api/school/students/updateFees/{studentFeeId}
Content-Type: application/json

Example: POST /api/school/students/updateFees/1

{
  "amount": 5000,
  "status": "paid"
}
```

### 11. Collect Fees
```http
POST /api/school/students/collectFees
Content-Type: application/json

{
  "student_id": 1,
  "amount": 5000,
  "payment_method": "cash"
}
```

### 12. Delete Fees
```http
DELETE /api/school/students/deleteFees/{studentFeeId}

Example: DELETE /api/school/students/deleteFees/1
```

### 13. Get Student Dashboard Details
```http
GET /api/school/student-dashboard-details
```

---

## 👨‍🏫 Teacher Routes

### 14. Register Teacher
```http
POST /api/school/teachers/register
Content-Type: application/json

{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "subject": "Mathematics",
  "phone": "1234567890"
  // Add other required fields
}
```

### 15. Get All Teachers
```http
GET /api/school/teachers/all
```

### 16. Get All Teachers (Alternative Route)
```http
GET /api/school/getAllTeachers
```

### 17. Get Teacher By ID
```http
GET /api/school/teachers/{teacherId}

Example: GET /api/school/teachers/1
```

### 18. Get Teacher By ID (Alternative Route)
```http
GET /api/school/teacher/{teacherId}

Example: GET /api/school/teacher/1
```

### 19. Update Teacher
```http
POST /api/school/teachers/update/{teacherId}
Content-Type: application/json

Example: POST /api/school/teachers/update/1

{
  "name": "Jane Updated",
  "email": "jane.updated@example.com"
}
```

### 20. Delete Teacher
```http
DELETE /api/school/teachers/delete/{teacherId}

Example: DELETE /api/school/teachers/delete/1
```

---

## 📅 Teacher Attendance Routes

### 21. Mark Attendance
```http
POST /api/school/teacher-attendance/mark
Content-Type: application/json

{
  "user_id": 1,
  "date": "2026-02-14",
  "status": "present",
  "check_in_time": "09:00:00"
}
```

### 22. Get Teacher Attendance By Date
```http
GET /api/school/teacher-attendance/today/{userId}

Example: GET /api/school/teacher-attendance/today/1
```

### 23. Get Teacher Attendance History
```http
GET /api/school/teacher-attendance/history/{userId}

Example: GET /api/school/teacher-attendance/history/1
```

---

## 🏖️ Teacher Leave Routes

### 24. Apply Leave
```http
POST /api/school/teacher-leaves/apply
Content-Type: application/json

{
  "user_id": 1,
  "start_date": "2026-02-20",
  "end_date": "2026-02-22",
  "reason": "Personal work",
  "leave_type": "casual"
}
```

### 25. Get Teacher Leave History
```http
GET /api/school/teacher-leaves/history/{userId}

Example: GET /api/school/teacher-leaves/history/1
```

---

## 🏫 Admin - School Holidays Routes

### 26. Create School Holiday
```http
POST /api/school/school-holidays
Content-Type: application/json

{
  "name": "Independence Day",
  "date": "2026-08-15",
  "description": "National Holiday"
}
```

### 27. Get School Holidays
```http
GET /api/school/school-holidays
```

### 28. Update School Holiday
```http
PUT /api/school/school-holidays/{holidayId}
Content-Type: application/json

Example: PUT /api/school/school-holidays/1

{
  "name": "Independence Day Updated",
  "date": "2026-08-15",
  "description": "National Holiday - Updated"
}
```

### 29. Delete School Holiday
```http
DELETE /api/school/school-holidays/{holidayId}

Example: DELETE /api/school/school-holidays/1
```

---

## 📊 Quick Test Summary

**Total Routes: 29**

- Authentication: 2 routes
- Students: 11 routes
- Teachers: 7 routes
- Teacher Attendance: 3 routes
- Teacher Leaves: 2 routes
- School Holidays: 4 routes

---

## 🧪 Testing Tools

### Using cURL (Command Line)
```bash
# Example: Login
curl -X POST http://localhost/Python_my/api/school/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'

# Example: Get All Teachers
curl -X GET http://localhost/Python_my/api/school/getAllTeachers
```

### Using Postman
1. Import this file as a collection
2. Set base URL: `http://localhost/Python_my/api/school`
3. Test each endpoint

### Using Thunder Client (VS Code)
1. Create a new collection
2. Add requests from this list
3. Test directly in VS Code

---

## ⚠️ Important Notes

1. **Authentication**: Some routes may require authentication. Add token to headers if needed:
   ```
   Authorization: Bearer {your-token}
   ```

2. **CSRF Token**: For web middleware, you may need CSRF token

3. **Database**: Ensure your database has the required tables and data

4. **Validation**: Each route has validation rules. Check controller for required fields

5. **Response Format**: All routes return JSON responses

---

## 🔍 Verify Routes Are Loaded

Run this command to see all routes:
```bash
php artisan route:list --path=api/school
```

Or to see all routes in JSON format:
```bash
php artisan route:list --json > routes.json
```
