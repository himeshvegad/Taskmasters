# TaskMasters - System Architecture Documentation

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                          │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Desktop  │  │  Tablet  │  │  Mobile  │  │  Browser │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
└─────────────────────────────────────────────────────────────┘
                            ↓ HTTP/HTTPS
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                        │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  HTML5 + Tailwind CSS + JavaScript + Font Awesome   │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                    APPLICATION LAYER                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Frontend   │  │   Backend    │  │    Admin     │     │
│  │    Pages     │  │   PHP Logic  │  │    Panel     │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│                                                              │
│  • Landing Page      • Authentication   • Order Management  │
│  • Registration      • Session Mgmt     • Payment Verify   │
│  • Login             • File Upload      • Status Update    │
│  • Services          • Order Processing • File Delivery    │
│  • Order Form        • Payment Logic                       │
│  • Checkout          • Email Notify                        │
│  • Dashboard                                               │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      DATA LAYER                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │    MySQL     │  │  File System │  │   Sessions   │     │
│  │   Database   │  │   (Uploads)  │  │   (PHP)      │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│                                                              │
│  • users            • uploads/       • $_SESSION            │
│  • orders           • payments/                             │
│  • services         • delivered/                            │
└─────────────────────────────────────────────────────────────┘
```

## Component Architecture

### 1. Frontend Components

```
Frontend/
├── Landing Page (index.php)
│   ├── Hero Section with Animations
│   ├── Search Bar
│   ├── Service Categories Grid
│   ├── Trust Indicators
│   └── Floating Action Buttons
│
├── Authentication
│   ├── Login (login.php)
│   └── Register (register.php)
│
├── Service Flow
│   ├── Service Selection (services.php)
│   ├── Order Form (order.php)
│   ├── Checkout (checkout.php)
│   └── User Dashboard (dashboard.php)
│
└── Legal Pages
    ├── Privacy Policy (privacy.php)
    ├── Terms of Service (terms.php)
    └── Refund Policy (refund.php)
```

### 2. Backend Components

```
Backend/
├── Configuration
│   ├── database.php (DB Connection)
│   └── setup.sql (Schema)
│
├── Authentication System
│   ├── User Registration
│   ├── Password Hashing
│   ├── Session Management
│   └── Role-Based Access
│
├── Order Management
│   ├── Order Creation
│   ├── File Upload Handler
│   ├── Status Tracking
│   └── Delivery System
│
└── Admin System
    ├── Order Verification
    ├── Payment Approval
    ├── Status Updates
    └── File Delivery
```

## Database Architecture

### Entity Relationship Diagram

```
┌─────────────────┐
│     USERS       │
├─────────────────┤
│ PK id           │
│    name         │
│    email        │◄────────┐
│    phone        │         │
│    password     │         │
│    is_admin     │         │
│    created_at   │         │
└─────────────────┘         │
                            │ 1:N
                            │
┌─────────────────┐         │
│    ORDERS       │         │
├─────────────────┤         │
│ PK id           │         │
│ FK user_id      │─────────┘
│    category     │
│    service_name │
│    price        │
│    title        │
│    work_summary │
│    deadline     │
│    special_inst │
│    file_path    │
│    status       │
│    payment_ver  │
│    payment_ss   │
│    delivered_f  │
│    created_at   │
└─────────────────┘

┌─────────────────┐
│   SERVICES      │
├─────────────────┤
│ PK id           │
│    category     │
│    name         │
│    price        │
│    description  │
└─────────────────┘
```

## Data Flow Diagrams

### User Order Flow

```
┌──────┐    Register/Login    ┌──────────┐
│ User │ ──────────────────► │  System  │
└──────┘                      └──────────┘
   │                               │
   │ Browse Services               │
   │ ──────────────────────────► │
   │                               │
   │ Select Service                │
   │ ──────────────────────────► │
   │                               │
   │ Fill Order Form               │
   │ ──────────────────────────► │
   │                               │
   │                          ┌────▼────┐
   │                          │ Create  │
   │                          │  Order  │
   │                          └────┬────┘
   │                               │
   │ ◄──────────────────────────── │
   │   Redirect to Checkout        │
   │                               │
   │ Upload Payment Screenshot     │
   │ ──────────────────────────► │
   │                               │
   │                          ┌────▼────┐
   │                          │  Store  │
   │                          │ Payment │
   │                          └────┬────┘
   │                               │
   │ ◄──────────────────────────── │
   │   Confirmation                │
   │                               │
   │ Track Order Status            │
   │ ◄──────────────────────────► │
   │                               │
   │ Download Delivered File       │
   │ ◄──────────────────────────── │
   │                               │
```

### Admin Workflow

```
┌───────┐   Login as Admin   ┌──────────┐
│ Admin │ ─────────────────► │  System  │
└───────┘                     └──────────┘
   │                               │
   │ View All Orders               │
   │ ◄──────────────────────────── │
   │                               │
   │ Select Order                  │
   │ ──────────────────────────► │
   │                               │
   │                          ┌────▼────┐
   │                          │  Fetch  │
   │                          │  Order  │
   │                          │ Details │
   │                          └────┬────┘
   │                               │
   │ ◄──────────────────────────── │
   │   Display Order Info          │
   │                               │
   │ Verify Payment Screenshot     │
   │ ──────────────────────────► │
   │                               │
   │                          ┌────▼────┐
   │                          │ Update  │
   │                          │ Payment │
   │                          │ Status  │
   │                          └────┬────┘
   │                               │
   │ Update Order Status           │
   │ ──────────────────────────► │
   │                               │
   │ Upload Delivered File         │
   │ ──────────────────────────► │
   │                               │
   │                          ┌────▼────┐
   │                          │  Mark   │
   │                          │Delivered│
   │                          └────┬────┘
   │                               │
   │ ◄──────────────────────────── │
   │   Confirmation                │
```

## API Endpoints (Current Implementation)

| Endpoint | Method | Purpose | Auth Required |
|----------|--------|---------|---------------|
| /index.php | GET | Landing page | No |
| /register.php | GET/POST | User registration | No |
| /login.php | GET/POST | User login | No |
| /logout.php | GET | User logout | Yes |
| /services.php | GET | Browse services | Yes |
| /order.php | GET/POST | Create order | Yes |
| /checkout.php | GET/POST | Payment | Yes |
| /dashboard.php | GET | User orders | Yes |
| /admin/dashboard.php | GET | Admin panel | Admin |
| /admin/order_details.php | GET/POST | Manage order | Admin |

## File Structure

```
Taskmasters/
│
├── config/
│   ├── database.php          # Database connection config
│   └── setup.sql             # Database schema & seed data
│
├── admin/
│   ├── dashboard.php         # Admin order list
│   └── order_details.php     # Order management interface
│
├── uploads/
│   ├── [user_files]          # Customer uploaded files
│   ├── payments/             # Payment screenshots
│   └── delivered/            # Completed work files
│
├── index.php                 # Landing page
├── login.php                 # Login page
├── register.php              # Registration page
├── logout.php                # Logout handler
├── services.php              # Service catalog
├── order.php                 # Order form
├── checkout.php              # Payment page
├── dashboard.php             # User dashboard
├── privacy.php               # Privacy policy
├── terms.php                 # Terms of service
├── refund.php                # Refund policy
├── header.php                # Header include
├── footer.php                # Footer include
├── .htaccess                 # Apache configuration
└── README.md                 # Documentation
```

## Technology Stack Details

### Frontend Technologies
- **HTML5**: Semantic markup, forms, file uploads
- **Tailwind CSS 3.x**: Utility-first styling via CDN
- **JavaScript (Vanilla)**: Form validation, interactivity
- **Font Awesome 6.4.0**: Icon library

### Backend Technologies
- **PHP 8.x**: Server-side logic
- **MySQL 8.x**: Relational database
- **Apache 2.4**: Web server
- **Session Management**: PHP native sessions

### Development Tools
- **XAMPP**: Local development environment
- **phpMyAdmin**: Database management
- **VS Code**: Code editor

## Security Implementation

### Current Security Features
✅ Password hashing (password_hash)
✅ SQL escaping (real_escape_string)
✅ Session management
✅ File upload validation
✅ Admin role verification
✅ .htaccess protection

### Production Security Needs
❌ Prepared statements (PDO)
❌ CSRF tokens
❌ Input sanitization
❌ XSS prevention
❌ Rate limiting
❌ HTTPS enforcement
❌ Environment variables
❌ Security headers

## Implementation Status

| Feature | Status | Notes |
|---------|--------|-------|
| Landing Page | ✅ Complete | With animations |
| User Registration | ✅ Complete | Email/Password |
| User Login | ✅ Complete | Session-based |
| Service Catalog | ✅ Complete | 3 categories |
| Order Form | ✅ Complete | With file upload |
| Payment System | ✅ Complete | UPI QR code |
| User Dashboard | ✅ Complete | Order tracking |
| Admin Panel | ✅ Complete | Full management |
| Legal Pages | ✅ Complete | Privacy/Terms/Refund |
| Responsive Design | ✅ Complete | Mobile-friendly |
| Email Notifications | ❌ Pending | Needs PHPMailer |
| WhatsApp Integration | ❌ Pending | Needs API |
| Google OAuth | ❌ Pending | Needs OAuth setup |
| Payment Gateway | ❌ Pending | Manual UPI only |
| Invoice Generation | ❌ Pending | Future feature |

## Deployment Architecture

### Development (Current)
```
Local Machine
├── XAMPP
│   ├── Apache (Port 80)
│   ├── MySQL (Port 3306)
│   └── PHP 8.x
└── Browser (localhost)
```

### Production (Recommended)
```
Shared Hosting / VPS
├── Apache/Nginx
├── PHP 8.x
├── MySQL 8.x
├── SSL Certificate
├── Domain Name
└── Email Server
```

## Performance Considerations

### Current Implementation
- Static CSS (CDN)
- Minimal JavaScript
- Direct database queries
- Session-based auth

### Optimization Opportunities
- Database indexing
- Query optimization
- Image compression
- Caching (Redis/Memcached)
- CDN for assets
- Lazy loading
- Code minification

## Scalability Path

### Phase 1 (Current)
- Single server
- Shared hosting
- Manual processes

### Phase 2 (Growth)
- VPS hosting
- Email automation
- Payment gateway
- Analytics

### Phase 3 (Scale)
- Load balancer
- Database replication
- CDN integration
- Microservices
- API development

## Maintenance Requirements

### Regular Tasks
- Database backups (daily)
- Security updates
- Log monitoring
- Performance monitoring
- User support

### Monitoring Metrics
- Server uptime
- Response time
- Error rates
- User registrations
- Order completion rate
- Payment success rate

## Support & Documentation

### User Documentation
- How to place order
- Payment instructions
- Order tracking guide
- Refund process

### Admin Documentation
- Order management
- Payment verification
- File delivery process
- User management

### Developer Documentation
- Code structure
- Database schema
- API endpoints
- Deployment guide

---

**Last Updated**: 2024
**Version**: 1.0.0
**Status**: Development Ready
