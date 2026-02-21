# TaskMasters - Visual Sitemap & Navigation Flow

## 🗺️ Complete Sitemap

```
TaskMasters Website
│
├── 🏠 HOME (index.php)
│   ├── Hero Section
│   ├── Search Bar
│   ├── Service Categories Preview
│   ├── Trust Indicators
│   └── CTA Section
│
├── 🔐 AUTHENTICATION
│   ├── Login (login.php)
│   │   └── → Dashboard / Admin Panel
│   │
│   └── Register (register.php)
│       └── → Login Page
│
├── 🛍️ SERVICES (services.php)
│   ├── Student Category
│   │   ├── Presentation (PPT) - ₹499
│   │   ├── Email Drafting - ₹199
│   │   ├── Schedule Maker - ₹199
│   │   └── Excel Work - ₹299
│   │
│   ├── Business Category
│   │   ├── Social Media Management - ₹9,999+
│   │   ├── Product Video (5-10s) - ₹399
│   │   ├── Product Video (20-25s) - ₹699
│   │   ├── Product Poster (1) - ₹199
│   │   └── Product Poster (5) - ₹599
│   │
│   └── Individual Category
│       ├── Custom Poster - ₹299
│       ├── Rent Poster - ₹399
│       ├── Wedding Invitation - ₹999
│       └── Photo Editing - ₹299
│
├── 📝 ORDER FLOW
│   ├── Order Form (order.php)
│   │   ├── Title Input
│   │   ├── File Upload
│   │   ├── Work Summary
│   │   ├── Deadline Picker
│   │   └── Special Instructions
│   │
│   ├── Checkout (checkout.php)
│   │   ├── Order Summary
│   │   ├── QR Code Display
│   │   ├── UPI Details
│   │   └── Payment Screenshot Upload
│   │
│   └── Dashboard (dashboard.php)
│       ├── Order List
│       ├── Status Tracking
│       └── File Download
│
├── 👨‍💼 ADMIN PANEL
│   ├── Admin Dashboard (admin/dashboard.php)
│   │   └── All Orders Table
│   │
│   └── Order Details (admin/order_details.php)
│       ├── Order Information
│       ├── Customer Details
│       ├── Payment Verification
│       ├── Status Update
│       └── File Upload
│
├── 📄 LEGAL PAGES
│   ├── Privacy Policy (privacy.php)
│   ├── Terms of Service (terms.php)
│   └── Refund Policy (refund.php)
│
└── 🔚 LOGOUT (logout.php)
```

## 🎯 User Journey Map

### New User Journey

```
┌─────────────────────────────────────────────────────────────┐
│                    DISCOVERY PHASE                           │
└─────────────────────────────────────────────────────────────┘
                            ↓
                    [Landing Page]
                            ↓
              Browse Services / Read About Us
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  REGISTRATION PHASE                          │
└─────────────────────────────────────────────────────────────┘
                            ↓
                    Click "Get Started"
                            ↓
                    [Registration Page]
                            ↓
              Fill: Name, Email, Phone, Password
                            ↓
                      Submit Form
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                    LOGIN PHASE                               │
└─────────────────────────────────────────────────────────────┘
                            ↓
                     [Login Page]
                            ↓
                Enter Email & Password
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  SERVICE SELECTION                           │
└─────────────────────────────────────────────────────────────┘
                            ↓
                   [Services Page]
                            ↓
          Choose Category (Student/Business/Individual)
                            ↓
                  Select Specific Service
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                    ORDER CREATION                            │
└─────────────────────────────────────────────────────────────┘
                            ↓
                    [Order Form]
                            ↓
              ┌─────────────┴─────────────┐
              │                           │
         Enter Details            Upload Files
              │                           │
              └─────────────┬─────────────┘
                            ↓
                    Submit Order
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                    PAYMENT PHASE                             │
└─────────────────────────────────────────────────────────────┘
                            ↓
                   [Checkout Page]
                            ↓
              View Order Summary & QR Code
                            ↓
              Make Payment via UPI App
                            ↓
              Upload Payment Screenshot
                            ↓
                  Confirm Payment
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                   TRACKING PHASE                             │
└─────────────────────────────────────────────────────────────┘
                            ↓
                    [Dashboard]
                            ↓
              View Order Status
              │
              ├─ Pending (Payment Verification)
              ├─ In Progress (Work in Progress)
              └─ Delivered (Download File)
                            ↓
                    Download File
                            ↓
                         END
```

### Returning User Journey

```
[Landing Page] → [Login] → [Dashboard]
                              ↓
                    View Previous Orders
                              ↓
                    Place New Order
                              ↓
                    [Services] → [Order] → [Checkout]
```

### Admin Journey

```
[Login as Admin]
        ↓
[Admin Dashboard]
        ↓
View All Orders (Table View)
        ↓
Click "View" on Order
        ↓
[Order Details Page]
        ↓
┌───────┴───────┐
│               │
View Customer   View Order
Details         Details
│               │
└───────┬───────┘
        ↓
View Payment Screenshot
        ↓
Verify Payment ✓
        ↓
Update Status: "In Progress"
        ↓
Work on Order (External)
        ↓
Upload Completed File
        ↓
Status Auto-Updates: "Delivered"
        ↓
Customer Can Download
        ↓
END
```

## 📱 Page Hierarchy

### Level 1: Public Pages (No Login Required)
```
├── index.php (Landing)
├── login.php
├── register.php
├── privacy.php
├── terms.php
└── refund.php
```

### Level 2: User Pages (Login Required)
```
├── services.php
├── order.php
├── checkout.php
└── dashboard.php
```

### Level 3: Admin Pages (Admin Login Required)
```
└── admin/
    ├── dashboard.php
    └── order_details.php
```

## 🔄 Navigation Patterns

### Main Navigation (All Pages)
```
┌─────────────────────────────────────────────────────┐
│  TaskMasters    [Home] [Services] [Login/Dashboard] │
└─────────────────────────────────────────────────────┘
```

### Footer Navigation (All Pages)
```
┌─────────────────────────────────────────────────────┐
│  Services  |  Legal  |  Contact                     │
│  - Student    - Privacy   - Email                   │
│  - Business   - Terms     - Phone                   │
│  - Individual - Refund                              │
└─────────────────────────────────────────────────────┘
```

### Floating Actions (All Pages)
```
                                    ┌──────┐
                                    │ 💬 WA│ (WhatsApp)
                                    └──────┘
                                    ┌──────┐
                                    │ ✉️ EM│ (Email)
                                    └──────┘
```

## 🎨 Page Layouts

### Landing Page Layout
```
┌─────────────────────────────────────────┐
│           NAVIGATION BAR                │
├─────────────────────────────────────────┤
│                                         │
│         HERO SECTION                    │
│         - Headline                      │
│         - Search Bar                    │
│         - Trust Badges                  │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│      SERVICE CATEGORIES                 │
│   [Student] [Business] [Individual]     │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│         CTA SECTION                     │
│    "Need Custom Help?"                  │
│   [WhatsApp] [Email]                    │
│                                         │
├─────────────────────────────────────────┤
│              FOOTER                     │
└─────────────────────────────────────────┘
```

### Services Page Layout
```
┌─────────────────────────────────────────┐
│           NAVIGATION BAR                │
├─────────────────────────────────────────┤
│                                         │
│      CATEGORY TABS                      │
│  [Student] [Business] [Individual]      │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│      SERVICE GRID                       │
│  ┌─────┐ ┌─────┐ ┌─────┐              │
│  │ PPT │ │Email│ │Sched│              │
│  │ ₹499│ │₹199 │ │₹199 │              │
│  └─────┘ └─────┘ └─────┘              │
│                                         │
└─────────────────────────────────────────┘
```

### Order Form Layout
```
┌─────────────────────────────────────────┐
│           NAVIGATION BAR                │
├─────────────────────────────────────────┤
│                                         │
│      SERVICE SUMMARY                    │
│   [Service Name] - ₹XXX                 │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│         ORDER FORM                      │
│   [Title Input]                         │
│   [File Upload]                         │
│   [Work Summary]                        │
│   [Deadline Picker]                     │
│   [Special Instructions]                │
│   [Submit Button]                       │
│                                         │
└─────────────────────────────────────────┘
```

### Checkout Page Layout
```
┌─────────────────────────────────────────┐
│           NAVIGATION BAR                │
├─────────────────────────────────────────┤
│                                         │
│  ┌──────────────┐  ┌──────────────┐   │
│  │ ORDER        │  │ PAYMENT      │   │
│  │ SUMMARY      │  │ DETAILS      │   │
│  │              │  │              │   │
│  │ Service: XXX │  │  [QR CODE]   │   │
│  │ Price: ₹XXX  │  │              │   │
│  │              │  │ UPI: xxx@upi │   │
│  │              │  │              │   │
│  │              │  │ [Upload SS]  │   │
│  │              │  │ [Confirm]    │   │
│  └──────────────┘  └──────────────┘   │
│                                         │
└─────────────────────────────────────────┘
```

### Dashboard Layout
```
┌─────────────────────────────────────────┐
│           NAVIGATION BAR                │
├─────────────────────────────────────────┤
│                                         │
│  My Orders          [+ New Order]       │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│  ┌─────────────────────────────────┐   │
│  │ Order #1 - PPT        [Pending] │   │
│  │ Deadline: 25 Dec      ₹499      │   │
│  └─────────────────────────────────┘   │
│                                         │
│  ┌─────────────────────────────────┐   │
│  │ Order #2 - Poster  [Delivered]  │   │
│  │ [Download File]       ₹299      │   │
│  └─────────────────────────────────┘   │
│                                         │
└─────────────────────────────────────────┘
```

### Admin Dashboard Layout
```
┌─────────────────────────────────────────┐
│         ADMIN NAVIGATION BAR            │
├─────────────────────────────────────────┤
│                                         │
│           ALL ORDERS                    │
│                                         │
│  ┌─────────────────────────────────┐   │
│  │ ID | Customer | Service | Status│   │
│  ├─────────────────────────────────┤   │
│  │ 1  | John     | PPT     |Pending│   │
│  │ 2  | Jane     | Poster  |InProg │   │
│  │ 3  | Bob      | Video   |Deliv  │   │
│  └─────────────────────────────────┘   │
│                                         │
└─────────────────────────────────────────┘
```

## 🔗 Internal Linking Structure

### From Landing Page
- → Login
- → Register
- → Services (Student)
- → Services (Business)
- → Services (Individual)
- → Privacy Policy
- → Terms of Service
- → Refund Policy
- → WhatsApp (External)
- → Email (External)

### From Services Page
- → Order Form (for each service)
- → Dashboard
- → Home

### From Order Form
- → Checkout
- → Services (Cancel)

### From Checkout
- → Dashboard (After payment)

### From Dashboard
- → Services (New Order)
- → Checkout (Pending payments)
- → Download (Delivered files)

### From Admin Dashboard
- → Order Details (Each order)
- → Logout

## 📊 Status Flow Diagram

```
Order Created
      ↓
┌─────────────┐
│   PENDING   │ ← Payment not verified
└─────────────┘
      ↓ Admin verifies payment
┌─────────────┐
│ IN PROGRESS │ ← Admin working on order
└─────────────┘
      ↓ Admin uploads file
┌─────────────┐
│  DELIVERED  │ ← Customer can download
└─────────────┘
```

## 🎯 Call-to-Action (CTA) Map

### Primary CTAs
1. **Landing Page**: "Get Started" → Register
2. **Services Page**: "Order Now" → Order Form
3. **Order Form**: "Proceed to Checkout" → Checkout
4. **Checkout**: "Confirm Payment" → Dashboard
5. **Dashboard**: "New Order" → Services

### Secondary CTAs
1. **Landing Page**: "Sign In" → Login
2. **Landing Page**: "WhatsApp" → External
3. **Landing Page**: "Email" → External
4. **Services**: Category Tabs → Filter
5. **Dashboard**: "Download" → File

## 🔐 Access Control Matrix

| Page | Public | User | Admin |
|------|--------|------|-------|
| index.php | ✅ | ✅ | ✅ |
| login.php | ✅ | ✅ | ✅ |
| register.php | ✅ | ✅ | ✅ |
| services.php | ❌ | ✅ | ✅ |
| order.php | ❌ | ✅ | ✅ |
| checkout.php | ❌ | ✅ | ✅ |
| dashboard.php | ❌ | ✅ | ❌ |
| admin/dashboard.php | ❌ | ❌ | ✅ |
| admin/order_details.php | ❌ | ❌ | ✅ |
| privacy.php | ✅ | ✅ | ✅ |
| terms.php | ✅ | ✅ | ✅ |
| refund.php | ✅ | ✅ | ✅ |

## 📱 Responsive Breakpoints

### Desktop (1920px+)
- Full navigation
- 3-column service grid
- Side-by-side layouts

### Laptop (1366px - 1919px)
- Full navigation
- 3-column service grid
- Optimized spacing

### Tablet (768px - 1365px)
- Hamburger menu (optional)
- 2-column service grid
- Stacked layouts

### Mobile (< 768px)
- Hamburger menu
- 1-column service grid
- Fully stacked layouts
- Touch-optimized buttons

## 🎨 Color Scheme

### Primary Colors
- **Purple**: `#7C3AED` (purple-600) - Primary brand
- **White**: `#FFFFFF` - Background
- **Gray**: `#1F2937` (gray-800) - Text

### Status Colors
- **Yellow**: `#FCD34D` (yellow-300) - Pending
- **Blue**: `#3B82F6` (blue-500) - In Progress
- **Green**: `#10B981` (green-500) - Delivered
- **Red**: `#EF4444` (red-500) - Error/Cancel

### Category Colors
- **Student**: Blue (`#3B82F6`)
- **Business**: Green (`#10B981`)
- **Individual**: Purple (`#7C3AED`)

---

**This sitemap provides a complete visual overview of the TaskMasters platform structure and navigation flow.**
