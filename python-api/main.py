from fastapi import FastAPI, File, UploadFile
import uvicorn
import io
import json
import os
import google.generativeai as genai
from PIL import Image
from dotenv import load_dotenv
from pydantic import BaseModel

# Load environment variables
load_dotenv()

app = FastAPI(title="RoadHealth AI - Gemini Integration")

# Configure Gemini API Key
GEMINI_API_KEY = os.getenv("GEMINI_API_KEY")
if not GEMINI_API_KEY:
    raise ValueError("GEMINI_API_KEY is not set. Please ensure it is present in your .env file.")
genai.configure(api_key=GEMINI_API_KEY)

def generate_content_with_fallback(contents, json_mode=False):
    models_to_try = ['gemini-3.5-flash', 'gemini-2.5-flash', 'gemini-2.0-flash']
    last_error = None
    for model_name in models_to_try:
        try:
            print(f"Attempting generation with model: {model_name}")
            model = genai.GenerativeModel(model_name)
            generation_config = {"response_mime_type": "application/json"} if json_mode else {}
            response = model.generate_content(contents, generation_config=generation_config)
            return response, model_name
        except Exception as e:
            last_error = e
            error_str = str(e)
            print(f"Model {model_name} failed: {error_str}")
            # If it's a quota or transient issue, continue to try the next model
            continue
    raise last_error

@app.get("/")
def read_root():
    return {"status": "ok", "message": "RoadHealth AI - Gemini API Server Running"}

class ChatRequest(BaseModel):
    query: str
    context: str

@app.post("/chat")
async def chat_assistant(request: ChatRequest):
    try:
        prompt = f"""
        You are an AI Pavement Civil Engineer Assistant named RoadHealth AI.
        You are chatting with a user on the dashboard.
        
        Use the following real-time database context to answer the user's question accurately.
        If the question cannot be answered using the context, use your general pavement engineering knowledge, but clarify that it's general advice.
        Keep your answer concise, professional, and conversational.
        
        CONTEXT (Live Database Stats):
        {request.context}
        
        USER QUESTION:
        {request.query}
        """
        
        response, model_used = generate_content_with_fallback(prompt, json_mode=False)
        return {"response": response.text.strip()}
    except Exception as e:
        print(f"Gemini Chat Error: {e}")
        return {"response": f"Sorry, I encountered an error: {str(e)}"}

@app.post("/analyze")
async def analyze_road(file: UploadFile = File(...)):
    """
    Analyzes a pavement image in real-time using Google Gemini 1.5 Flash.
    """
    try:
        contents = await file.read()
        image = Image.open(io.BytesIO(contents))
        
        prompt = """
        You are an image classifier and a professional AI Pavement Civil Engineer.
        
        CRITICAL STEP 1: VALIDATION
        First, carefully examine the image. Is it actually a photograph of a road, pavement, or street surface?
        If the image is a diagram, chart, document, person, animal, car interior, building, or ANY other non-road object, you MUST return this exact JSON and do no further analysis:
        {
            "condition": "Invalid",
            "pci_score": 0,
            "severity": "None",
            "recommended_action": "No road recognized. The uploaded image does not appear to be a road or pavement surface. Please upload a valid image of a road.",
            "detections": []
        }
        
        CRITICAL STEP 2: ANALYSIS
        ONLY if the image IS clearly a photograph of a road or pavement, analyze it and detect defects. You MUST return a JSON object with the following fields:
        
        1. "condition": String. Must be exactly one of: ['Excellent', 'Good', 'Fair', 'Poor', 'Critical']
        2. "pci_score": Integer between 0 (failed pavement) and 100 (pristine pavement) based on ASTM D6433 standards.
        3. "severity": String. Must be exactly one of: ['None', 'Low', 'Medium', 'High']
        4. "recommended_action": String. A professional, detailed maintenance strategy or repair guidance.
        5. "detections": Array of detected defects. Each item MUST have:
           - "label": String. One of: ['pothole', 'alligator_crack', 'longitudinal_crack', 'transverse_crack', 'minor_crack', 'surface_wear', 'patch']
           - "confidence": Float between 0.50 and 1.00 representing detection confidence.
           - "count": Integer representing the number of occurrences of this specific defect in the image.
        """
        
        response, model_used = generate_content_with_fallback(
            [image, prompt],
            json_mode=True
        )
        
        # Clean up response text if it contains markdown JSON blocks
        cleaned_text = response.text.strip()
        if cleaned_text.startswith("```json"):
            cleaned_text = cleaned_text[7:]
        elif cleaned_text.startswith("```"):
            cleaned_text = cleaned_text[3:]
        if cleaned_text.endswith("```"):
            cleaned_text = cleaned_text[:-3]
        cleaned_text = cleaned_text.strip()
        
        result = json.loads(cleaned_text)
        
        # Calculate total defects based on the detections list
        if "detections" in result and isinstance(result["detections"], list):
            total_defects = sum(int(item.get("count", 1)) for item in result["detections"])
        else:
            total_defects = 0
            
        result["total_defects"] = total_defects
        result["mode"] = f"live ({model_used})"
        
        return result
        
    except Exception as e:
        print(f"Gemini API Error: {e}")
        # Return invalid so frontend doesn't show a fake score on error
        return {
            "condition": "Invalid",
            "pci_score": 0,
            "severity": "None",
            "recommended_action": f"System Error: {str(e)}",
            "detections": []
        }

if __name__ == "__main__":
    uvicorn.run("main:app", host="0.0.0.0", port=8001, reload=True)
