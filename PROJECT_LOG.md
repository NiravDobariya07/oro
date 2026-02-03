# OroCommerce Project: CustomerNotesBundle - Development Log

This document provides a comprehensive history of the project development, from the initial installation to the current implementation of features and security.

## 1. Initial Setup and Installation
*   **Application**: OroCommerce CRM Application (v6.1).
*   **Database**: Configured with PostgreSQL.
*   **Installation Process**:
    *   Encountered and resolved multiple database schema migration failures related to `oro_migration` and `oro_entity_config`.
    *   Successfully completed installation using `php bin/console oro:platform:install`.
    *   Configured base parameters in `.env` and `parameters.yml`.

## 2. SMTP and Communication Setup
*   **SMTP Configuration**:
    *   Host: `smtp.gmail.com`
    *   Port: `465` (SSL)
    *   Username: `evertaxrelief@gmail.com`
    *   Purpose: Resolved the issue of not receiving export emails and system notifications.
    *   Verified functionality using Gmail's SMTP server.

## 3. Bundle Development: AcmeCustomerNotesBundle
*   **Purpose**: Created a custom bundle to manage customer-specific notes.
*   **Entity Implementation**: Created the `CustomerNote` entity with the following fields:
    *   `id` (Primary Key)
    *   `note` (Text)
    *   `username` (String)
    *   `createdAt` (DateTime)
    *   `owner` (Link to User - for ACL)
    *   `organization` (Link to Organization - for ACL)
*   **Controller**: Implemented `CustomerNoteController` to handle CRUD operations.
*   **Forms**: Created `CustomerNoteType` for entering and editing notes.

## 4. UI and Datagrid Enhancements
*   **Datagrid Configuration**: 
    *   Defined `acme-customer-note-grid` in `datagrids.yml`.
    *   Added columns: `id`, `note`, `username`, `createdAt`.
    *   Implemented Sorting: Allowed sorting by date and username.
    *   Implemented Filtering: Added filters for text search and date range.
*   **Export Functionality**: enabled CSV export (replacing default ZIP compression for large datasets).
*   **Routing**: Set up frontend and backend routes for viewing and managing notes.

## 4. System Configuration
*   **SMTP Setup**:
    *   Configured Gmail SMTP for outgoing emails.
    *   Verified email delivery for system notifications and exports.
*   **Translations**: Created `messages.en.yml` for entity labels and ACL descriptions.

## 5. Security and Access Control (ACL)
*   **ACL Configuration**: 
    *   Defined permissions in `Resources/config/oro/acl.yml`.
    *   Applied `#[AclAncestor]` annotations in the controller to restrict access.
*   **Ownership Model**: 
    *   Configured `USER` level ownership to allow users to manage their own notes.
    *   Implemented `owner` and `organization` fields to support standard Oro security layers.
*   **Troubleshooting**: 
    *   Investigated discrepancies where `owner_type` was showing as `NONE` in the system config.
    *   Synchronized entity configuration using `oro:entity-config:update`.

## 6. Major Bug Fixes and Optimization
*   **500 Errors**: Resolved internal server errors on the list page related to missing entity metadata.
*   **Schema Sync**: Managed several `doctrine:schema:update` cycles to align the database with entity changes.
*   **Cache Management**: Performed deep cache clears (`cache:clear`, `oro:entity-config:cache:clear`) to resolve configuration loading issues.

---
*Created on: 2026-02-03*
