with open("storage/logs/laravel.log", "r", encoding="utf-8", errors="ignore") as f:
    lines = f.readlines()

print("Searching laravel.log for errors...")
error_count = 0
for i in range(len(lines) - 1, -1, -1):
    line = lines[i]
    if "TestimonialController" in line or "content" in line or "Review" in line or "IntegrityConstraintViolationException" in line:
        # print around the error
        start = max(0, i - 2)
        end = min(len(lines), i + 8)
        print(f"--- Log excerpt near line {i}: ---")
        print("".join(lines[start:end]))
        print("-" * 30)
        error_count += 1
        if error_count > 10:
            break
