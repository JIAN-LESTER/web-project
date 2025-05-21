import sys
import json
from sentence_transformers import SentenceTransformer

model = SentenceTransformer('all-MiniLM-L6-v2')

# Support UTF-8 and large input (CLI args are limited!)
text = sys.stdin.read().strip()

embedding = model.encode(text).tolist()
print(json.dumps(embedding))
