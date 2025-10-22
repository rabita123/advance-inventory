# Product Replacement Test

## Test the new functionality:

1. **Go to "Upcoming Expired Products"** in your admin panel
2. **Find a product that is expired or expiring soon**
3. **Click "Update Expiry" button**
4. **Select a different product** in the "New Product" dropdown
5. **Fill in supplier and replacement date**
6. **Submit the replacement**

## What should happen:

### During Replacement:
- Original Mac product gets updated with Asus details
- Replacement record stores original Mac details in new columns

### In Replacement History:
- **Left side (Original Product)**: Shows Mac name, code, expiry (before replacement)
- **Right side (Source Product)**: Shows Asus name, code, expiry (used for replacement)

## Verification Steps:

1. **Check Product List**: The Mac product should now show Asus details
2. **Check Replacement History**: Should show Mac vs Asus comparison
3. **Check Database**: `product_replacements` table should have original_* columns filled

## Example Expected Result:

**Original Product (Left):**
- Name: "Mac Laptop"
- Code: "MAC001" 
- Expiry: "Oct 15, 2025" (expired)

**Source Product (Right):**
- Name: "Asus Laptop"
- Code: "ASUS001"
- Expiry: "Dec 31, 2025" (fresh)

The functionality is now complete and ready to test!