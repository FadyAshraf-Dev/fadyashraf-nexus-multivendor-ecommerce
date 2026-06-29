const ELEMENTS = {
  form: "addProductForm",

  costPrice: "inputCostPrice",
  sellingPrice: "inputSellingPrice",

  stockQuantity: "inputStockQuantity",
  lowStock: "inputLowStockThreshold",

  discountType: "selectDiscountType",
  discountValue: "inputDiscountValue",
};

document.addEventListener("DOMContentLoaded", initializePage);

function initializePage() {
  const dom = createDom(ELEMENTS);

  initializeFormValidation(dom.form);

  initializeWizardNavigation();

  initializeScrollBehavior();

  initializeDiscountToggle(dom);

  initializeDynamicConstraints(dom);

  initializeWizardValidation();
}

/* =====================================================
   Wizard Validation
===================================================== */

