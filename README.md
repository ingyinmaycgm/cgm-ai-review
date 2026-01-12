# 🤖 CGM AI Review

A standalone code review tool for the CGM team. It uses **Groq AI (Llama 3)** to analyze your code changes instantly.

## 🚀 One-Click Setup

No need to install PHP, Docker, or any dependencies. Just download the file for your system.

### 1. Download & Install
* **macOS Users:**
    1. Download `ai-review-mac`.

    2. Open Terminal in your Downloads folder and run:
       `chmod +x ai-review-mac`
  
    3. Move it to your Applications or PATH.
* **Windows Users:**
    1. Download `ai-review.exe`.
    2. No installation required.

### 2. Configuration (First Time Only)
Run the config command to save your Groq API Key:
```bash
# Mac
./ai-review-mac config

# Windows
ai-review.exe config
```
Paste your Groq API Key when prompted. (Get your key at https://console.groq.com)

### 2. How to Use
  1. Go to your project folder (Java, C#, React Native, etc.).
  2. Stage your code changes: git add .
  3. Run the review:
```bash
ai-review review
```