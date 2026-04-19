import re

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

# Generate the HTML for the avatars
avatars_html = ""
for i, img in enumerate(images):
    checked = "checked" if i == 0 else ""
    avatars_html += f"""
            <label class="avatar-label">
                <div class="avatar-img-container">
                    <img src="{img}" alt="Avatar {i+1}">
                </div>
                <input type="radio" name="avatar" value="{img}" class="avatar-radio" {checked}>
            </label>"""

with open(r'c:\E\iti-open-source\Freelance\elixira\resources\views\Reviews\form.html', 'r', encoding='utf-8') as f:
    form_html = f.read()

# Inject the avatars into form_html
form_html = re.sub(r'<div class="avatars-wrapper">.*?</div>\s*<!-- Section 2', f'<div class="avatars-wrapper">{avatars_html}\n        </div>\n\n        <!-- Section 2', form_html, flags=re.DOTALL)

with open(r'c:\E\iti-open-source\Freelance\elixira\resources\views\Reviews\form.html', 'w', encoding='utf-8') as f:
    f.write(form_html)

# Now inject it into index.blade.php
with open(r'c:\E\iti-open-source\Freelance\elixira\resources\views\testimonials\index.blade.php', 'r', encoding='utf-8') as f:
    index_content = f.read()

# Replace <div class="framer-10odmvv" data-framer-name="Features"> down to </body>
new_content = re.sub(r'<div class="framer-10odmvv" data-framer-name="Features">.*</body>', form_html + "\n</body>", index_content, flags=re.DOTALL)

with open(r'c:\E\iti-open-source\Freelance\elixira\resources\views\testimonials\index.blade.php', 'w', encoding='utf-8') as f:
    f.write(new_content)
    
print("Successfully replaced content!")
