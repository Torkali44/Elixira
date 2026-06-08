with open("storage/logs/laravel.log", "r", encoding="utf-8", errors="ignore") as f:
    lines = f.readlines()

print(f"Total lines in laravel.log: {len(lines)}")
for line in lines[-100:]:
    print(line.strip())
