{% load static %}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Restaurant Finder App</title>
    <link rel="stylesheet" href="{% static 'css/styles.css' %}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            position: relative;
        }
        
        .profile-header {
            background: var(--header-gradient);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" opacity="0.1"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>');
            background-repeat: repeat;
            opacity: 0.1;
        }
        
        .profile-header h1 {
            margin-bottom: 10px;
            font-size: 2.2rem;
            font-weight: 700;
        }
        
        .profile-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--text-color);
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--input-border);
            border-radius: 5px;
            font-size: 16px;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        
        .form-control:focus {
            border-color: var(--button-primary);
            outline: none;
        }
        
        .profile-image-container {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .profile-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload-btn {
            background-color: var(--button-primary);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            cursor: pointer;
            display: inline-block;
            margin-top: 10px;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .file-upload-btn:hover {
            background-color: var(--button-hover);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        
        .profile-image-overlay {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
            cursor: pointer;
        }
        
        .profile-image-container:hover .profile-image-overlay {
            opacity: 1;
        }
        
        .profile-image-overlay i {
            color: white;
            font-size: 40px;
        }
        
        .file-name {
            margin-top: 10px;
            font-size: 14px;
            color: var(--text-color);
            background: rgba(0, 0, 0, 0.05);
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
        }
        
        .submit-btn {
            background-color: var(--button-primary);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: var(--button-hover);
        }
        
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .cancel-btn {
            background-color: #e74c3c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 48%;
            transition: all 0.3s;
        }
        
        .cancel-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .save-btn {
            background-color: #2ecc71;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 48%;
            transition: all 0.3s;
        }
        
        .save-btn:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }
        
        .back-link {
            margin-top: 20px;
            display: block;
            text-align: center;
            color: var(--button-primary);
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }

        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 22px;
            color: white;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            overflow: hidden;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .theme-toggle::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: var(--header-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50%;
        }
        
        .theme-toggle:hover::before {
            opacity: 1;
        }
        
        .theme-toggle i {
            position: relative;
            z-index: 1;
        }

        .theme-toggle:hover {
            transform: rotate(30deg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        
        .error-message p {
            font-weight: 600;
            color: #e74c3c;
            margin-bottom: 10px;
        }
        
        .error-message i {
            margin-right: 5px;
        }
        
        .error-message ul {
            list-style-type: none;
            padding-left: 20px;
        }
        
        .error-message li {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .success-message {
            background-color: rgba(46, 204, 113, 0.1);
            border-left: 4px solid #2ecc71;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .success-message p {
            font-weight: 600;
            color: #2ecc71;
            margin: 0;
        }
        
        .success-message i {
            margin-right: 5px;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="theme-toggle" id="theme-toggle">
            <i class="fas fa-moon"></i>
        </div>
        
        <div class="profile-header">
            <h1>Your Profile</h1>
            <p>Customize your restaurant discovery experience</p>
        </div>
        
        <div class="profile-form">
            <form method="post" enctype="multipart/form-data">
                {% csrf_token %}
                
                {% if form.errors %}
                <div class="error-message">
                    <p><i class="fas fa-exclamation-circle"></i> Please correct the errors below:</p>
                    <ul>
                        {% for field in form %}
                            {% for error in field.errors %}
                                <li>{{ field.label }}: {{ error }}</li>
                            {% endfor %}
                        {% endfor %}
                        {% for error in form.non_field_errors %}
                            <li>{{ error }}</li>
                        {% endfor %}
                    </ul>
                </div>
                {% endif %}
                
                {% if messages %}
                    {% for message in messages %}
                        <div class="success-message">
                            <p><i class="fas fa-check-circle"></i> {{ message }}</p>
                        </div>
                    {% endfor %}
                {% endif %}
                
                <div class="profile-image-container">
                    {% if profile.profile_picture %}
                        <img src="{{ profile.profile_picture.url }}" alt="Profile Picture" class="profile-image">
                    {% else %}
                        <img src="{% static 'img/default-profile.png' %}" alt="Default Profile" class="profile-image">
                    {% endif %}
                    
                    <label for="{{ form.profile_picture.id_for_label }}" class="profile-image-overlay">
                        <i class="fas fa-camera"></i>
                    </label>
                    
                    <div class="file-upload">
                        <label for="{{ form.profile_picture.id_for_label }}" class="file-upload-btn">
                            <i class="fas fa-camera"></i> Change Profile Photo
                        </label>
                        <div class="file-name" id="file-name">No file selected</div>
                        {{ form.profile_picture }}
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="{{ form.name.id_for_label }}">Name</label>
                    {{ form.name }}
                </div>
                
                <div class="form-group">
                    <label for="{{ form.location.id_for_label }}">Location</label>
                    {{ form.location }}
                </div>
                
                <div class="form-group">
                    <label for="{{ form.bio.id_for_label }}">Bio</label>
                    {{ form.bio }}
                </div>
                
                <div class="btn-container">
                    <a href="{% url 'home' %}" class="cancel-btn">Cancel</a>
                    <button type="submit" class="save-btn">Save Profile</button>
                </div>
            </form>
            
            <a href="{% url 'home' %}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
    
    <script>
        // File upload preview
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('{{ form.profile_picture.id_for_label }}');
            const profileImage = document.querySelector('.profile-image');
            const fileName = document.getElementById('file-name');
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            
            // Handle file input change
            fileInput.addEventListener('change', function() {
                // Reset any previous error styles
                fileName.style.color = '';
                fileName.style.backgroundColor = '';
                
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    // Check file size
                    if (file.size > maxSize) {
                        fileName.textContent = 'File too large (max 5MB)';
                        fileName.style.color = '#e74c3c';
                        fileName.style.backgroundColor = 'rgba(231, 76, 60, 0.1)';
                        // Clear the file input
                        this.value = '';
                        return;
                    }
                    
                    // Check file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        fileName.textContent = 'Invalid file type (use JPG, PNG, GIF)';
                        fileName.style.color = '#e74c3c';
                        fileName.style.backgroundColor = 'rgba(231, 76, 60, 0.1)';
                        // Clear the file input
                        this.value = '';
                        return;
                    }
                    
                    // Valid file, show filename
                    fileName.textContent = file.name;
                    
                    // Preview image
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImage.src = e.target.result;
                        
                        // Add animation effect
                        profileImage.style.transform = 'scale(1.05)';
                        setTimeout(() => {
                            profileImage.style.transform = 'scale(1)';
                        }, 300);
                    };
                    reader.readAsDataURL(file);
                } else {
                    fileName.textContent = 'No file selected';
                }
            });
            
            // Check for saved theme preference
            const currentTheme = localStorage.getItem('theme');
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = themeToggle.querySelector('i');
            
            // Apply saved theme or default
            if (currentTheme === 'dark-mode') {
                document.body.classList.add('dark-mode');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
            
            // Toggle dark/light mode
            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark-mode');
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                } else {
                    localStorage.setItem('theme', '');
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                }
            });
        });
    </script>
</body>
</html> 