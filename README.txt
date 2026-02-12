# Joomla! GSoC '26 Prototype: Automated Workflow Scheduler

**Project:** Automated Workflow Scheduler
**Organization:** Joomla! (Google Summer of Code 2026)
**Author:** Yash Verma

## 🚀 Overview
This repository contains a **Proof of Concept (MVP)** for the "Automated Workflow Scheduler" project. It demonstrates a modified Joomla 5.x architecture that introduces **Time-Aware Transitions** to the core Workflow component.

Current functionality allows administrators to attach a specific time delay (e.g., 20 seconds) to a workflow transition. The system intercepts the state change, halts execution for the defined duration, and strictly enforces the delay before committing the new state to the database.

> **Note:** This is a prototype developed to validate the database schema and event interception logic. The final GSoC project will replace the synchronous wait mechanisms with the asynchronous `com_scheduler`.

## 🛠️ Key Technical Features

### 1. Database Schema Extension
* **Modification:** Extended the core `#__workflow_transitions` table.
* **New Column:** `auto_delay` (Integer) – Stores the delay duration in seconds.
* **Result:** Persistent storage of scheduling metadata for every workflow transition.

### 2. Native UI Integration
* **Location:** `Administrator > Content > Workflows > Transitions > Edit`
* **Implementation:** Layout override of the Transition Edit view.
* **Feature:** A native "Automation Delay (Seconds)" input field that seamlessly integrates into the existing Joomla UX.

### 3. Event Interception Logic
* **Hook:** `onWorkflowBeforeTransition`
* **Logic:**
    1.  Intercepts the transition request before the database update.
    2.  Checks the `auto_delay` value for the specific transition ID.
    3.  If a delay exists, it halts the process (Synchronous Wait in MVP).
    4.  Resumes and commits the state change only after the timer expires.

## 🧪 How to Test the Prototype

1.  **Install:** Set up this repository as a standard Joomla site on your local server (XAMPP/WAMP).
2.  **Configure:**
    * Go to **Content > Workflows** and select a Workflow.
    * Click on a **Transition** (e.g., "Publish").
    * Enter `20` in the **Automation Delay** field and Save.
3.  **Execute:**
    * Go to **Content > Articles**.
    * Change an article's status using the transition you modified.
    * **Observe:** The browser will wait for 20 seconds, and a blue notification bar will confirm: *"Automated delay of 20 seconds completed."*

## 📂 Repository Structure
This repository includes the full Joomla CMS source code to demonstrate the core integration. Key modified files include:
* `administrator/components/com_workflow/tmpl/transition/edit.php` (UI Override)
* `plugins/system/workflow_scheduler/` (Custom Event Interceptor)
* `installation/sql/mysql/joomla.sql` (Schema adjustments)

## 🔮 Future Roadmap (GSoC Implementation)
The primary goal of the GSoC coding phase is to evolve this synchronous prototype into a fully asynchronous system:
* **Replace `sleep()`:** Integrate `com_scheduler` to handle delays in the background.
* **Task Queue:** Implement a "Pending Transitions" queue in the dashboard.
* **Complex Triggers:** Support absolute dates (e.g., "Jan 1st") and relative offsets (e.g., "+1 Year").

---
*This prototype is submitted as part of the GSoC 2026 proposal process.*
