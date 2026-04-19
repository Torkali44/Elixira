import os

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

avatars_html = ""
for i, img in enumerate(images):
    checked = 'checked' if i == 0 else ''
    avatars_html += f"""                <label class="avatar-label">
                    <div class="avatar-img-container">
                        <img src="{img}" alt="Avatar {i+1}">
                    </div>
                    <input type="radio" name="avatar" value="{img}" class="avatar-radio" {checked}>
                </label>\n"""

blade_content = f"""@extends('layouts.framer')

@section('title', 'Testimonials — Elixira')

@section('content')
<style>
    .testimonials-page {{
        background-color: #0b161c;
        padding: 60px 20px;
    }}

    .review-form-container {{
        max-width: 900px;
        margin: 0 auto;
        padding: 40px;
        background-color: #0d1a20;
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        font-family: 'Istok Web', sans-serif;
        color: #888;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
    }}

    .review-section-title {{
        font-size: 15px;
        margin-bottom: 25px;
        color: #aaa;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }}

    /* Avatars Grid */
    .avatars-wrapper {{
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 15px;
        margin-bottom: 50px;
        justify-items: center;
    }}

    .avatar-label {{
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        gap: 15px;
        transition: transform 0.3s ease;
    }}

    .avatar-img-container {{
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.03);
        padding: 3px;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }}

    .avatar-img-container img {{
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }}

    .avatar-radio {{
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.15);
        cursor: pointer;
        transition: all 0.3s ease;
        outline: none;
    }}

    /* Glow Effects */
    .avatar-radio:checked {{
        background-color: #fff;
        border-color: #4ac8f6;
        box-shadow: inset 0 0 0 4px #4ac8f6, 0 0 15px rgba(74, 200, 246, 0.6);
    }}

    .avatar-label:has(.avatar-radio:checked) .avatar-img-container {{
        border-color: #4ac8f6;
        box-shadow: 0 0 20px rgba(74, 200, 246, 0.35);
        background-color: rgba(74, 200, 246, 0.1);
        transform: scale(1.1);
    }}

    .avatar-label:hover .avatar-img-container {{
        transform: scale(1.08);
    }}

    /* Form Fields Styling */
    .form-wrapper {{
        display: flex;
        flex-direction: column;
        gap: 25px;
    }}

    .form-row {{
        display: flex;
        gap: 20px;
    }}

    .form-group {{
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }}

    .form-label {{
        font-size: 14px;
        color: #888;
        padding-left: 5px;
    }}

    .form-input {{
        width: 100%;
        padding: 15px 20px;
        background-color: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        color: #fff;
        font-family: inherit;
        font-size: 15px;
        transition: all 0.3s ease;
        outline: none;
    }}

    .form-input::placeholder {{
        color: rgba(255, 255, 255, 0.2);
    }}

    .form-input:focus {{
        border-color: #4ac8f6;
        box-shadow: 0 0 0 2px rgba(74, 200, 246, 0.15);
        background-color: rgba(0, 0, 0, 0.4);
    }}

    select.form-input {{
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234ac8f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 20px center;
        cursor: pointer;
    }}

    select.form-input option {{
        background-color: #0d1a20;
        color: #fff;
    }}

    textarea.form-input {{
        resize: vertical;
        min-height: 140px;
        line-height: 1.6;
    }}

    /* Newsletter */
    .newsletter-group {{
        display: inline-flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 5px;
    }}

    .newsletter-checkbox {{
        width: 20px;
        height: 20px;
        border-radius: 6px;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.15);
        appearance: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }}

    .newsletter-checkbox:checked {{
        background-color: #4ac8f6;
        border-color: #4ac8f6;
    }}

    .newsletter-checkbox:checked::after {{
        content: '✓';
        color: #000;
        font-size: 14px;
        font-weight: 900;
    }}

    .newsletter-text {{
        font-size: 13px;
        color: #777;
    }}

    /* Submit Button */
    .submit-btn-wrapper {{
        display: flex;
        justify-content: flex-end;
        margin-top: 15px;
    }}

    .submit-btn {{
        background: linear-gradient(135deg, rgba(74, 200, 246, 0.15) 0%, rgba(74, 200, 246, 0.05) 100%);
        border: 1px solid rgba(74, 200, 246, 0.4);
        color: #4ac8f6;
        padding: 14px 40px;
        border-radius: 40px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        letter-spacing: 0.5px;
    }}

    .submit-btn:hover {{
        background: rgba(74, 200, 246, 0.25);
        box-shadow: 0 8px 25px rgba(74, 200, 246, 0.3);
        transform: translateY(-3px) scale(1.02);
        color: #fff;
        border-color: #fff;
    }}

    /* Responsive */
    @media (max-width: 992px) {{
        .avatars-wrapper {{ grid-template-columns: repeat(6, 1fr); }}
    }}
    @media (max-width: 768px) {{
        .avatars-wrapper {{ grid-template-columns: repeat(4, 1fr); }}
        .form-row {{ flex-direction: column; gap: 20px; }}
        .review-form-container {{ padding: 25px; }}
    }}
    @media (max-width: 480px) {{
        .avatars-wrapper {{ grid-template-columns: repeat(3, 1fr); }}
    }}
</style>

<div class="testimonials-page framer-17fm0qq">
    <div class="review-form-container">
        @if(session('success'))
            <div style="text-align: center; color: #4ac8f6; padding: 20px; font-size: 1.1rem; background: rgba(74, 200, 246, 0.1); border-radius: 16px; margin-bottom: 30px; border: 1px solid rgba(74, 200, 246, 0.2);">
                <i class="fas fa-check-circle" style="margin-right: 10px;"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{{{ route('testimonials.store') }}}}" method="POST">
            @csrf
            <input type="hidden" name="type" value="direct">

            <!-- Section 1: Avatars -->
            <div class="review-section-title">Select Your Avatar</div>
            
            <div class="avatars-wrapper">
{avatars_html}            </div>

            <!-- Section 2: Form Inputs -->
            <div class="form-wrapper">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-input" placeholder="e.g. Ryo Hazoki" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" placeholder="e.g. duke@elixira.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Age Range</label>
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
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-input" required>
                            <option value="" disabled selected>Select Stars...</option>
                            <option value="5">5 Stars - Excellent</option>
                            <option value="4">4 Stars - Very Good</option>
                            <option value="3">3 Stars - Good</option>
                            <option value="2">2 Stars - Fair</option>
                            <option value="1">1 Star - Poor</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">How would you describe your experience with us?</label>
                    <textarea name="content" class="form-input" placeholder="Share your thoughts..." required></textarea>
                </div>

                <label class="newsletter-group">
                    <input type="checkbox" name="newsletter" class="newsletter-checkbox">
                    <span class="newsletter-text">Keep me in the loop about new features and member benefits</span>
                </label>

                <div class="submit-btn-wrapper">
                    <button type="submit" class="submit-btn">
                        <span>Send Reflection</span>
                        <i class="fas fa-paper-plane" style="margin-left: 10px; font-size: 0.9em;"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
"""

with open(r'c:\E\iti-open-source\Freelance\elixira\resources\views\testimonials\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade_content)

print("Updated index.blade.php with layout extension, 24 avatars, and polished styling.")
