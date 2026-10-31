# HRMS & Employee Management System -- Update Plan

**Document Type:** Feature Update / Development Scope\
**Version:** 1.0\
**Date:** 30 October 2026\
**Status:** Proposed

------------------------------------------------------------------------

## Overview

This update expands the existing HRMS and Employee Management System
with employee-profile improvements, organization-wide document sharing,
employee letter requests, travel and expense workflows, project-based
timesheets, and advanced administration reports.

The work is divided into immediate upgrades and new modules so that the
existing application can be improved incrementally without introducing
unnecessary changes to the current system.

## 1. Immediate Upgrades --- Existing Pages

These improvements can be added to the current codebase with limited
database changes.

### 1.1 Automatic Employee ID Generation

-   Generate employee IDs automatically when an employee record is
    created.
-   Use the existing employee ID format, such as `EMP-001`.
-   Determine the next sequence number from existing employee records.
-   Display the generated ID in the employee creation form.
-   Validate uniqueness on the backend before saving.
-   Handle concurrent employee creation safely to prevent duplicate IDs.

**Example:** `EMP-026`, `EMP-027`

**Implementation note:** Do not rely only on the last inserted employee.
Use a database-safe sequence, unique constraint, or transaction/locking
strategy so deleted records and simultaneous requests do not create
duplicate IDs.

### 1.2 Profile Photo Lightbox

-   Make employee profile photos clickable.
-   Open the full-resolution image in a centered modal/lightbox.
-   Add a dimmed page overlay and a clear close control.
-   Allow closing by clicking outside the image or pressing `Escape`.
-   Keep the interaction responsive on desktop and mobile.

### 1.3 Expanded Employee 360° Profile

Extend the employee profile with the following sections:

**Education Details** - Qualification - Institution / University -
Specialization - Passing year - Grade / Percentage

**National Identity** - PAN number - Aadhaar number (India) or SSN where
applicable - Other identity document type and number, if required

**Bank Account Details** - Account holder name - Bank name - Account
number - IFSC / routing code - Branch

**Security and privacy requirements** - Restrict access to sensitive
identity and bank details by role. - Mask sensitive values in normal
views. - Validate and protect data in storage and in transit. - Avoid
displaying sensitive information in logs or general exports. - Collect
only information required by the organization and applicable law.

### 1.4 Employee Dashboard Mini-Widgets

Add compact widgets to the employee dashboard.

**Last 7 Days Attendance** - Show attendance for the previous seven
days. - Use clear status indicators: - Green --- Present - Red ---
Absent - Yellow --- Late / Partial, according to configured attendance
rules - Include dates and a legend so the colors are understandable.

**Today's Logs** - Display a chronological timeline of today's
attendance events. - Include check-in, break, check-out, and other
supported log events. - Show the event time and relevant status.

------------------------------------------------------------------------

## 2. Organization Files & Document Sharing

**Module:** Organization Files\
**Access:** Admin upload and management; employee download based on
access rules.

### 2.1 Purpose

The existing Document Vault supports documents shared between an
administrator and an individual employee. This module adds
organization-wide and targeted document distribution.

### 2.2 File Visibility Types

**Global Files** - Visible to all active employees. - Examples: holiday
calendar, company policies, employee handbook, general announcements.

**Department-Based Files** - Visible only to employees in selected
departments. - Example: Sales Playbook shared with the Sales department.

**Designation-Based Files** - Visible only to employees with selected
designations. - Example: Manager SOP shared with department managers.

### 2.3 Admin Features

-   Upload, edit metadata, replace, archive, and delete files.
-   Set file title, description, category, and visibility type.
-   Select one or more departments or designations for restricted files.
-   Set publication and expiry dates, if needed.
-   View upload details and document availability.

### 2.4 Employee Portal Features

-   Add a **Company Files** tab to the employee portal.
-   List only files the logged-in employee is authorized to access.
-   Support file download and basic file details.
-   Organize files by category and publication date.

### 2.5 Suggested Data Structure

-   `organization_files`: file metadata, storage path, visibility type,
    status, dates, and uploader.
-   `organization_file_departments`: mapping between files and
    departments.
-   `organization_file_designations`: mapping between files and
    designations.

Use the existing department and designation tables wherever possible
rather than duplicating organization data.

------------------------------------------------------------------------

## 3. HR Letter Requests

**Module:** HR Letter Requests\
**Workflow:** Employee request → Admin review → Letter upload → Employee
download

### 3.1 Employee Request Form

Allow employees to request:

-   Bonafide Letter
-   Address Proof Letter
-   Visa Invitation Letter
-   Experience Letter

Each request should capture: - Request type - Reason / purpose -
Additional notes, if required - Submission date

### 3.2 Admin Request Management

-   Display all requests in an admin listing.
-   Filter by request type, employee, date, and status.
-   View the employee's reason and request details.
-   Approve, reject, or mark a request as in progress.
-   Upload the completed and signed PDF against the request.
-   Add an admin response or rejection reason.

### 3.3 Employee Tracking

Employees should be able to: - View submitted requests. - Track request
status. - Read admin remarks. - Download the completed letter when it is
available.

### 3.4 Suggested Statuses

`Pending` → `In Progress` → `Completed`

A request may also be marked `Rejected` or `Cancelled`.

### 3.5 Future Enhancement

Automated PDF generation can be introduced later using
organization-approved letter templates and verified employee data.

------------------------------------------------------------------------

## 4. Travel & Expense Management

**Module:** Travel & Expense Management

### 4.1 Travel Requests

Employees can submit travel requests before making bookings.

**Request fields** - Travel purpose - Source and destination - Departure
and return dates - Estimated budget - Mode of travel - Client / project
reference, if applicable - Additional notes

**Approval workflow** 1. Employee submits the request. 2. Manager or
Admin reviews the request. 3. Reviewer approves, rejects, or requests
changes. 4. Employee can view the decision and remarks.

### 4.2 Expense Claims

Employees can submit reimbursement claims with: - Expense date - Expense
category - Amount and currency - Description / business purpose -
Receipt or invoice attachment - Related travel request or project, if
applicable

**Initial expense categories** - Meals - Transport - Client Meeting -
Accommodation - Other

### 4.3 Expense Status Tracking

Use the following workflow:

`Pending` → `Approved` → `Reimbursed`

Also support `Rejected` and `Needs Correction` where appropriate.

### 4.4 Admin Features

-   Review submitted claims and attached receipts.
-   Approve, reject, or request corrections.
-   Record reimbursement date and payment reference.
-   Filter claims by employee, category, date, and status.
-   View total claimed, approved, and reimbursed amounts.

------------------------------------------------------------------------

## 5. Timesheets & Billable Hours

**Module:** Timesheets\
**Priority:** Advanced

Attendance records when an employee is present at work. Timesheets
record how working time is spent on projects, clients, or internal
activities. These should remain separate modules with optional
integration.

### 5.1 Projects / Jobs

Admin can create and manage: - Project or job name - Client name -
Project description - Start and end dates - Assigned employees - Project
status

### 5.2 Employee Time Logging

Employees can log: - Project / job - Work date - Start time and end
time, or total duration - Work description - Optional task reference

Provide validation to prevent invalid or overlapping time entries.

### 5.3 Billable and Non-Billable Time

-   Add a billable / non-billable selection to each time entry.
-   Allow authorized admins to review and correct entries.
-   Optionally require manager approval before entries are included in
    reports.

### 5.4 Admin Reports

Provide summaries for: - Total hours per employee - Total hours per
project - Billable hours - Non-billable hours - Billable utilization by
employee or project

Financial value should be calculated only when an authorized billing
rate has been configured.

------------------------------------------------------------------------

## 6. Advanced Admin Reports

**Route:** `/admin/reports`

Create a dedicated reporting dashboard with date filters and
export/print options.

### 6.1 Attendance Anomaly Report

Highlight attendance patterns that may require review: - Frequent late
arrivals - Repeated early check-outs - High absence counts - Missing
check-in or check-out records

Allow filtering by employee, department, and date range. Define
thresholds in settings rather than hard-coding them.

### 6.2 Leave Balance & Liability Report

Display: - Employee-wise leave balances - Leave balances by leave type -
Department-wise unused leave totals - Leave taken and remaining during
the selected period

If monetary liability is required, calculate it only using the
organization's approved leave-encashment policy and configured salary
basis. Unused leave days alone should not be presented as a financial
liability.

### 6.3 Asset Inventory Report

Provide a printable asset allocation report containing: - Asset name and
category - Asset tag / serial number - Assigned employee - Department -
Issue date - Asset condition - Return date and status

Include filters for employee, department, asset category, and allocation
status.

------------------------------------------------------------------------

## 7. Suggested Implementation Sequence

The following sequence reduces disruption to existing HRMS
functionality.

  -----------------------------------------------------------------------
  Phase                   Scope                   Priority
  ----------------------- ----------------------- -----------------------
  Phase 1                 Automatic employee IDs, High
                          profile photo lightbox, 
                          employee profile        
                          expansion, dashboard    
                          widgets                 

  Phase 2                 Organization Files      High
                          module and employee     
                          Company Files tab       

  Phase 3                 HR Letter Requests and  High
                          signed PDF upload       

  Phase 4                 Travel Requests and     Medium
                          Expense Claims          

  Phase 5                 Projects, Timesheets,   Medium
                          and billable-hour       
                          tracking                

  Phase 6                 Advanced Admin Reports  Medium
                          and exports             
  -----------------------------------------------------------------------

Each phase should be tested and approved before moving to the next
phase.

## 8. Common Technical & Security Requirements

-   Reuse existing authentication, roles, departments, designations,
    employee records, and file-storage conventions.
-   Enforce authorization on the backend for every view, download,
    update, approval, and report endpoint.
-   Validate all incoming data on the server, even when frontend
    validation exists.
-   Store uploaded files securely; do not expose private files through
    predictable public URLs.
-   Restrict file types and sizes, validate uploads, and use generated
    storage names.
-   Record important actions such as approvals, rejections, document
    uploads, and sensitive-data changes in an audit log.
-   Use database transactions for workflows that update multiple related
    records.
-   Add indexes and foreign-key constraints for new relationships.
-   Provide pagination, filtering, and sensible empty/loading/error
    states.
-   Ensure the interface is responsive and consistent with the existing
    HRMS design.
-   Add automated tests for permissions, validation, status transitions,
    and key calculations.
-   Back up the database and uploaded files before production
    migrations.

## 9. Acceptance Criteria

The update can be considered ready for release when:

1.  Employee IDs are generated uniquely and safely.
2.  Profile photos open in a responsive lightbox.
3.  Authorized users can maintain employee education, identity, and bank
    details securely.
4.  Employees can see their attendance summary and today's logs.
5.  Organization files are visible only to the intended audience.
6.  Employees can submit HR letter requests and download completed
    letters.
7.  Travel requests support review and approval.
8.  Expense claims support receipt uploads and reimbursement tracking.
9.  Timesheets record project hours and distinguish billable from
    non-billable work.
10. Admin reports display accurate, filterable data and can be printed
    or exported.
11. Role permissions, file access, and sensitive information have been
    tested.

## 10. Scope Notes & Dependencies

-   This document describes the proposed feature scope, not a
    confirmation that the features have already been implemented.
-   Existing database schemas, controllers, routes, middleware, and UI
    components should be reviewed before development begins.
-   Final table names, API contracts, approval hierarchy, retention
    rules, and permission rules should be confirmed during technical
    design.
-   PDF automation, advanced financial calculations, and integrations
    with accounting or payroll systems are future enhancements unless
    separately approved.
-   Delivery estimates should be prepared after reviewing the current
    codebase and confirming the approval workflows.

------------------------------------------------------------------------

**End of Update Plan**
