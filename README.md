# 🤖 CGM AI Review

A standalone code review tool for the CGM team. It uses **Groq AI (Llama 3)** to analyze your code changes instantly.

## 🚀 One-Click Setup

No need to install PHP, Docker, or any dependencies. Just download the file for your system.

### 1. Download [👉 Download cgm-ai-review](https://github.com/ingyinmaycgm/cgm-ai-review/releases/download/groq.v.1.0.1/cgm-ai-review)
* **macOS Users:**
    1. Download `ai-review-mac`.

    2. Open Terminal in your Downloads folder and run:
       `chmod +x ai-review-mac`
  
    3. Move it to your Applications or PATH.
* **Windows Users:**
    1. Download `cgm-ai-reivew`.
    2. Move it to a permanent folder like C:\aitools\, and add that folder path to your system's Environment Variables (Path) so you can run cgm-ai-review from any terminal.
    3. No installation required.

### 2. Configuration (First Time Only)
Run the config command to save your Groq API Key:
```bash
# Mac
./ai-review-mac config

# Windows
cgm-ai-review config
```
### 🔑 2. Getting your Groq API Key (Free)
Your tool requires a Groq API key to talk to the AI. Follow these steps:
1.  **Register:** Go to the [Groq Cloud Console](https://console.groq.com/login).
2.  **Login:** You can quickly sign in using your **Google (Gmail)** account.
3.  **Create Key:** Click on **"API Keys"** in the left sidebar.
4.  **Generate:** Click the **"Create API Key"** button. 
5.  **Copy:** Give it a name (e.g., "CGM Code Review") and copy the key immediately (it starts with `gsk_...`).

### ⚙️ 3. Configuration (First Time Only)
Run the config command and paste the key you just copied:
```bash
# Windows
cgm-ai-review config

```

### 4. How to Use
  1. Go to your project folder (Java, C#, React Native, etc.).
  2. Stage your code changes: git add .
  3. Run the review:
```bash
cgm-ai-review review
```
The AI will analyze your diff for:
1. 🐞 Potential bugs and logic errors.
2. 🔒 Security vulnerabilities.
3. ⚡ Performance improvements.
4. 🧹 Code cleanliness.

## What the AI Checks
The AI acts as a Senior Architect and checks for:
1. Layer Violations: Ensures DB logic stays in DAO/Repository and Business logic in Services.
2. Bug Detection: Logic errors, assignment bugs, and unhandled exceptions.
3. DB Standards: Use of Transactions for writes and avoidance of queries in loops (N+1).
4. Cleanliness: Proper documentation, no hardcoded values, and removal of debug code (console.log, dd(), etc.).

Security: Search for leaked API keys or SQL injection risks.
## 🛠 For Developers (Source Installation)
If you want to contribute to this tool or run it from source:
1. Clone the repo: `git clone https://github.com/ingyinmaycgm/cgm-ai-review.git`
2. Install dependencies: composer install
3. Run locally: `php cgm-ai-review review`
4. Build the binary:  `php cgm-ai-review app:build cgm-ai-review.phar`
5. Convert exe: `./vendor/bin/phpacker build windows x64 --src=./builds/cgm-ai-review.phar && mv builds/build/windows/windows-x64.exe builds/build/windows/cgm-ai-review`
