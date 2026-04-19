html_content = """<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap');
        
        body {
            background-color: #0b161c;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .review-form-container {
            width: 100%;
            max-width: 900px;
            padding: 40px;
            background-color: #0d1a20; /* Dark, matching the theme */
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            font-family: 'Istok Web', sans-serif;
            color: #888;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .review-section-title {
            font-size: 14px;
            margin-bottom: 20px;
            color: #888;
        }

        /* Avatars Section */
        .avatars-wrapper {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 15px;
            margin-bottom: 40px;
            justify-items: center;
        }

        .avatar-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            gap: 15px;
        }

        .avatar-img-container {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 3px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .avatar-img-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-radio {
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            outline: none;
        }

        /* Checked State */
        .avatar-radio:checked {
            background-color: #fff;
            border-color: #0099ff;
            box-shadow: inset 0 0 0 4px #0099ff, 0 0 10px rgba(0, 153, 255, 0.5);
        }

        .avatar-label:has(.avatar-radio:checked) .avatar-img-container {
            border-color: #4ac8f6;
            box-shadow: 0 0 15px rgba(74, 200, 246, 0.3);
            background-color: rgba(74, 200, 246, 0.1);
        }

        .avatar-label:hover .avatar-img-container {
            transform: scale(1.05);
        }

        /* Form Section */
        .form-wrapper {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 14px;
            color: #888;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            background-color: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(136, 136, 136, 0.2);
            border-radius: 12px;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: rgba(136, 136, 136, 0.4);
        }

        .form-input:focus {
            border-color: #4ac8f6;
            box-shadow: 0 0 0 1px #4ac8f6, inset 0 0 8px rgba(74, 200, 246, 0.1);
            background-color: rgba(0, 0, 0, 0.4);
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23888' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
            cursor: pointer;
        }

        select.form-input option {
            background-color: #13252D;
            color: #fff;
            padding: 10px;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 120px;
        }

        /* Newsletter checkbox */
        .newsletter-group {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            margin-top: -5px;
        }

        .newsletter-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            appearance: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .newsletter-checkbox:checked {
            background-color: #4ac8f6;
            border-color: #4ac8f6;
        }

        .newsletter-checkbox:checked::after {
            content: '✓';
            color: #000;
            font-size: 14px;
            font-weight: 900;
        }

        .newsletter-text {
            font-size: 13px;
            color: #888;
        }

        /* Submit Button */
        .submit-btn-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .submit-btn {
            background: rgba(74, 200, 246, 0.1);
            border: 1px solid rgba(74, 200, 246, 0.3);
            color: #4ac8f6;
            padding: 12px 35px;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: inset 0 0 10px rgba(74, 200, 246, 0.1);
            letter-spacing: 0.5px;
        }

        .submit-btn:hover {
            background: rgba(74, 200, 246, 0.2);
            box-shadow: inset 0 0 15px rgba(74, 200, 246, 0.3), 0 0 15px rgba(74, 200, 246, 0.2);
            transform: translateY(-2px);
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .avatars-wrapper {
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
            }
            .form-row {
                flex-direction: column;
                gap: 20px;
            }
            .review-form-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

    <div class="review-form-container">
        @if(session('success'))
            <div style="text-align: center; color: #4ac8f6; padding: 20px; font-size: 1.2rem; background: rgba(74, 200, 246, 0.1); border-radius: 10px; margin-bottom: 20px; border: 1px solid rgba(74, 200, 246, 0.2);">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('testimonials.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="direct">

            <!-- Section 1: Avatars -->
            <div class="review-section-title">Avatar</div>
            
            <div class="avatars-wrapper">
"""

images = [
    'https://framerusercontent.com/images/cTc7CUtNbTmlTgoiKuHSwOHME.png',
    'https://framerusercontent.com/images/xujOvWlIH4jCpEHwRSO8fL3AZyM.png',
    'https://framerusercontent.com/images/voEeLI8QvLxIBheChMgZpIZDBDw.png',
    'https://framerusercontent.com/images/P6B3UqKPpI7pUX8hpOGEuB7DoYI.png',
    'https://framerusercontent.com/images/ZOmjcnCegPgIJe774bHLeiqGoRY.png',
    'https://framerusercontent.com/images/aH4TSB4QigZBUovRTOzJbNfmE8.png',
    'https://framerusercontent.com/images/vjNbwG6wtp9Zat3QDbEDG5SQ8nc.png',
    'https://framerusercontent.com/images/yLvdWmXt1qfpzFexvRPL1YPjEM.png',
    'https://framerusercontent.com/images/L0MgVueQuuaTbIG2RDygjv6nxw.png',
    'https://framerusercontent.com/images/cyZY6rN0VQ2rTCXAp8vDkwwfs.png',
    'https://framerusercontent.com/images/avsgw3MlrBZ7Qemx2LDUzfksapA.png',
    'https://framerusercontent.com/images/tNsNvr6rtFzILJLih7KTMe4uM.png',
    'https://framerusercontent.com/images/QHfWARUm32FA9v9bgBIZeTDFaB8.png',
    'https://framerusercontent.com/images/epaiBXj1vYRcJ7bEafNzHniJ8gQ.png',
    'https://framerusercontent.com/images/yMGFyp1B3WEQPWVHuC2AOcqCwBk.png',
    'https://framerusercontent.com/images/AW5gsxnLBvhE7bhUymsSWpcAP0.png',
    'https://framerusercontent.com/images/0pOGEkOl3QOA0AOhql07dLjouU.png',
    'https://framerusercontent.com/images/Xdax07q3fD8YGG6qtDgZOZEaqI.png',
    'https://framerusercontent.com/images/rMJN1hMOPP8cSGd8LdmmlMesy8.png',
    'https://framerusercontent.com/images/iyDK7k3FedurGjdTkG1KSJYm8no.png',
    'https://framerusercontent.com/images/Ryq4xjuMhGxgQzm7NiX6xlq3938.png',
    'https://framerusercontent.com/images/YCyyVb7j8C5U4vDHPAPqKNHIfAc.png',
    'https://framerusercontent.com/images/CD114mrqzRMe6TQ3ieg8ZQxUk.png',
    'https://framerusercontent.com/images/7wRDToSNM4pfN5ZhkkMQRo4zuY.png'
]

for i, img in enumerate(images):
    checked = "checked" if i == 0 else ""
    html_content += f"""                <label class="avatar-label">
                    <div class="avatar-img-container">
                        <img src="{img}" alt="Avatar {i+1}">
                    </div>
                    <input type="radio" name="avatar" value="{img}" class="avatar-radio" {checked}>
                </label>\n"""

html_content += """            </div>

            <!-- Section 2: Form Inputs -->
            <div class="form-wrapper">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-input" placeholder="Ryo Hazoki" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="DukeNukem@framer.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <select name="age" class="form-input" required>
                            <option value="" disabled selected>Select...</option>
                            <option value="18-24">18 - 24 years</option>
                            <option value="25-34">25 - 34 years</option>
                            <option value="35-44">35 - 44 years</option>
                            <option value="45+">45+ years</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-input" required>
                            <option value="" disabled selected>Select...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-input" required>
                            <option value="" disabled selected>Select...</option>
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Review</label>
                    <textarea name="content" class="form-input" placeholder="Elixira's Awesome..." required></textarea>
                </div>

                <label class="newsletter-group">
                    <input type="checkbox" name="newsletter" class="newsletter-checkbox">
                    <span class="newsletter-text">Keep me in the loop about new features and member benefits</span>
                </label>

                <div class="submit-btn-wrapper">
                    <button type="submit" class="submit-btn">Send Reflection</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>
"""

with open(r'c:\E\iti-open-source\Freelance\elixira\resources\views\testimonials\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(html_content)

print("Created beautifully clean standalone HTML for index.blade.php")
