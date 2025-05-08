# process_document.py


import sentence_transformers

import sys
import json

model = sentence_transformers('all-MiniLM-L6-v2')

# Example: Assume first CLI arg is the path to the text file
file_path = sys.argv[1]

# Read file content
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Split content into chunks (basic example, adjust as needed)
chunks = [content[i:i+500] for i in range(0, len(content), 500)]

# Generate embeddings
results = []
for chunk in chunks:
    embedding = model.encode(chunk).tolist()
    results.append({
        'content': chunk,
        'embedding': embedding
    })

# Output JSON
print(json.dumps(results))
