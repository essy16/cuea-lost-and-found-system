# CUEA Lost & Found System V8

Changes in V8:
- Reporting a found item from the public landing page returns the user to the found-report form immediately after login.
- Lost-item reports can be submitted by students, visitors, staff, and admins. Normal users can track both lost and found reports from My Panel.
- Registration now asks for account category: Student or Visitor. Student registration number is required only for Student.
- My Panel includes a personal activity/audit trail.
- Staff cannot issue an item that the same staff account personally reported.
- Issue Items and Issued Items are combined into one page with Pending Issues first and Issued Items below.
- Public Landing Page link was removed from the staff sidebar.
- Existing Lost/Found, Claimed/Not Claimed, and Category filters are retained.

## Database upgrade
Run `sql/upgrade_v8.sql` once against your existing `cuea_lost_found` database. It only expands the users.role enum to include `visitor`; existing data is preserved.
