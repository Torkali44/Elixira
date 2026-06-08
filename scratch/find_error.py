import json

with open(r"C:\Users\SAMA\.gemini\antigravity-ide\brain\fb8638f3-2e97-4e9d-82fa-024d709695f6\.system_generated\logs\transcript.jsonl", "r", encoding="utf-8") as f:
    for i, line in enumerate(f):
        if i in [162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172]:
            data = json.loads(line)
            print(f"Step {data.get('step_index')}:")
            if 'thinking' in data:
                print(f"THINKING: {data['thinking']}")
            if 'content' in data:
                print(f"CONTENT: {data['content'][:1000]}")
            print("-" * 50)
