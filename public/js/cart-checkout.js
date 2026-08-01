(function (window) {
    'use strict';

    let cartConfig = null;
    let currentDeliveryFee = 0;
    let sharedShippingActive = false;
    let currentCurrency = '';
    let deliveryFeesById = {};

    function els() {
        return {
            city: document.getElementById('delivery_city_id'),
            feeDisplay: document.getElementById('cart-delivery-fee-display'),
            grandTotal: document.getElementById('cart-grand-total-display'),
            sharedInput: document.getElementById('shared_shipping_order_input'),
            sharedHidden: document.getElementById('shared_shipping_order_id'),
            sharedCheck: document.getElementById('shared-shipping-check-btn'),
            sharedClear: document.getElementById('shared-shipping-clear-btn'),
            sharedStatus: document.getElementById('shared-shipping-status'),
        };
    }

    function formatAmount(amount) {
        const value = Number(amount);
        if (!Number.isFinite(value)) {
            return '0';
        }
        if (Math.abs(value - Math.round(value)) < 0.001) {
            return String(Math.round(value));
        }

        return String(Number(value.toFixed(2)));
    }

    function formatMoney(amount, currency) {
        const formatted = formatAmount(amount);

        return cartConfig.locale === 'ar' ? (formatted + ' ' + currency) : (currency + ' ' + formatted);
    }

    function rebuildDeliveryFeesMap() {
        const { city } = els();
        if (!city) {
            return;
        }

        try {
            const fromAttr = city.getAttribute('data-fees');
            if (fromAttr) {
                const parsed = JSON.parse(fromAttr);
                Object.keys(parsed || {}).forEach(function (key) {
                    deliveryFeesById[String(key)] = parseFloat(parsed[key]);
                });
            }
        } catch (e) {
            // Ignore malformed fee map attributes.
        }

        Array.from(city.options || []).forEach(function (option) {
            if (!option.value) {
                return;
            }

            const feeAttr = option.getAttribute('data-fee');
            if (feeAttr !== null && feeAttr !== '') {
                deliveryFeesById[String(option.value)] = parseFloat(feeAttr);
            }
        });
    }

    function resolveSelectedCityFee() {
        const { city } = els();
        if (!city || !city.value) {
            return 0;
        }

        const cityId = String(city.value);
        if (Object.prototype.hasOwnProperty.call(deliveryFeesById, cityId)) {
            const mapped = parseFloat(deliveryFeesById[cityId]);
            if (Number.isFinite(mapped)) {
                return mapped;
            }
        }

        const selected = city.options[city.selectedIndex];
        const feeAttr = selected ? selected.getAttribute('data-fee') : null;
        const parsed = feeAttr !== null ? parseFloat(feeAttr) : NaN;
        if (Number.isFinite(parsed)) {
            return parsed;
        }

        const label = selected ? (selected.textContent || '') : '';
        const match = label.match(/(\d+(?:\.\d+)?)/);

        return match ? parseFloat(match[1]) : 0;
    }

    function updateGrandTotal() {
        const { grandTotal } = els();
        if (grandTotal) {
            grandTotal.textContent = formatMoney(cartConfig.subtotal + currentDeliveryFee, currentCurrency);
        }
    }

    function applyDeliveryCitySelection() {
        const { city, feeDisplay } = els();

        if (sharedShippingActive) {
            currentDeliveryFee = 0;
            if (feeDisplay) {
                feeDisplay.textContent = cartConfig.i18n.sharedFree;
            }
            updateGrandTotal();

            return;
        }

        if (!city) {
            return;
        }

        const cityId = city.value;
        currentDeliveryFee = cityId ? resolveSelectedCityFee() : 0;
        if (feeDisplay) {
            feeDisplay.textContent = cityId ? formatMoney(currentDeliveryFee, currentCurrency) : '—';
        }
        updateGrandTotal();
    }

    function setSharedShippingStatus(message, isError) {
        const { sharedStatus } = els();
        if (!sharedStatus) {
            return;
        }

        sharedStatus.style.display = 'block';
        sharedStatus.style.color = isError ? '#ff8a8a' : '#9be7b4';
        sharedStatus.textContent = message;
    }

    function setAddressFieldsLocked(locked) {
        const addressField = document.getElementById('address') || document.querySelector('input[name="address"]');
        const addressNew = document.getElementById('address_new');
        const addressSelect = document.getElementById('selected_address');
        const saveControls = document.getElementById('new_address_controls');

        [addressField, addressNew].forEach(function (field) {
            if (!field) {
                return;
            }

            field.readOnly = locked;
            field.style.opacity = locked ? '0.85' : '';
            field.style.cursor = locked ? 'not-allowed' : '';
            field.style.background = locked ? 'rgba(255,255,255,0.04)' : '';
        });

        if (addressSelect) {
            addressSelect.disabled = locked;
            addressSelect.style.opacity = locked ? '0.85' : '';
            addressSelect.style.cursor = locked ? 'not-allowed' : '';
        }

        if (saveControls) {
            saveControls.style.display = locked ? 'none' : saveControls.style.display;
        }
    }

    function clearSharedShipping(showStatus) {
        const { city, sharedHidden, sharedClear, sharedCheck, sharedStatus } = els();
        sharedShippingActive = false;
        if (sharedHidden) {
            sharedHidden.value = '';
        }
        if (sharedClear) {
            sharedClear.style.display = 'none';
        }
        if (sharedCheck) {
            sharedCheck.style.display = '';
            sharedCheck.disabled = false;
        }
        if (city) {
            city.disabled = false;
            city.required = city.style.display !== 'none';
        }
        if (showStatus && sharedStatus) {
            sharedStatus.style.display = 'none';
            sharedStatus.textContent = '';
        }
        setAddressFieldsLocked(false);
        applyDeliveryCitySelection();
    }

    function applySharedShipping(data) {
        const { city, sharedHidden, sharedInput, sharedClear, sharedCheck } = els();
        sharedShippingActive = true;
        if (sharedHidden) {
            sharedHidden.value = String(data.order_id);
        }
        if (sharedInput) {
            sharedInput.value = String(data.order_reference || data.order_id || '');
        }
        if (sharedClear) {
            sharedClear.style.display = '';
        }
        if (sharedCheck) {
            sharedCheck.style.display = 'none';
        }
        if (city && data.delivery_city_id) {
            city.value = String(data.delivery_city_id);
            city.disabled = true;
            city.required = false;
        }

        const addressField = document.getElementById('address') || document.querySelector('input[name="address"]');
        const addressNew = document.getElementById('address_new');
        const addressSelect = document.getElementById('selected_address');
        if (addressField && data.address) {
            addressField.value = data.address;
        }
        if (addressNew && data.address) {
            addressNew.value = data.address;
            addressNew.style.display = 'none';
        }
        if (addressSelect && data.address) {
            let matched = false;
            Array.from(addressSelect.options).forEach(function (option) {
                if (option.value === data.address) {
                    option.selected = true;
                    matched = true;
                }
            });
            if (!matched) {
                addressSelect.value = '__new__';
                if (addressNew) {
                    addressNew.style.display = 'none';
                    addressNew.value = data.address;
                }
            }
        }

        setAddressFieldsLocked(true);
        setSharedShippingStatus(data.message || cartConfig.i18n.sharedApplied, false);
        applyDeliveryCitySelection();
    }

    function currentPhoneCountry() {
        const input = document.querySelector('input[name="country_code"]');

        return (input && input.value) ? input.value : '+966';
    }

    function checkSharedShippingOrder() {
        const { sharedInput, sharedCheck } = els();
        const reference = (sharedInput && sharedInput.value ? sharedInput.value : '').trim().toUpperCase();
        if (!reference) {
            clearSharedShipping(true);

            return;
        }

        if (sharedInput) {
            sharedInput.value = reference;
        }

        if (sharedCheck) {
            sharedCheck.disabled = true;
        }

        fetch(cartConfig.sharedLookupUrl + '?reference=' + encodeURIComponent(reference) + '&phone_country=' + encodeURIComponent(currentPhoneCountry()), {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { response: response, data: data || {} };
                }).catch(function () {
                    return { response: response, data: {} };
                });
            })
            .then(function (result) {
                if (result.response.ok && result.data.ok) {
                    applySharedShipping(result.data);

                    return;
                }

                clearSharedShipping(false);
                const validationMessage = result.data.errors && result.data.errors.reference
                    ? result.data.errors.reference[0]
                    : null;
                const message = validationMessage || result.data.message || (result.data.status === 'country_mismatch'
                    ? cartConfig.i18n.sharedCountryMismatch
                    : (result.data.status === 'already_shipped'
                        ? cartConfig.i18n.sharedShipped
                        : cartConfig.i18n.sharedNotFound));
                setSharedShippingStatus(message, true);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: cartConfig.i18n.errorTitle, text: message });
                }
            })
            .catch(function () {
                clearSharedShipping(false);
                setSharedShippingStatus(cartConfig.i18n.sharedNotFound, true);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: cartConfig.i18n.errorTitle, text: cartConfig.i18n.sharedNotFound });
                }
            })
            .finally(function () {
                const latest = els().sharedCheck;
                if (latest) {
                    latest.disabled = false;
                }
            });
    }

    function loadDeliveryCities(phoneCountry) {
        const { city, feeDisplay } = els();
        if (!city || sharedShippingActive) {
            return;
        }

        const previous = city.value;
        city.innerHTML = '<option value="">' + cartConfig.i18n.loadingCities + '</option>';
        city.required = true;
        currentDeliveryFee = 0;
        if (feeDisplay) {
            feeDisplay.textContent = '—';
        }
        updateGrandTotal();

        fetch(cartConfig.deliveryCitiesUrl + '?phone_country=' + encodeURIComponent(phoneCountry), {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                city.innerHTML = '';
                deliveryFeesById = {};
                currentCurrency = data.currency_label || data.currency || currentCurrency;
                if (!data.cities || data.cities.length === 0) {
                    city.style.display = 'none';
                    city.required = false;
                    city.innerHTML = '<option value=""></option>';
                    city.setAttribute('data-fees', '{}');
                    applyDeliveryCitySelection();

                    return;
                }

                city.style.display = 'block';
                city.required = !sharedShippingActive;
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = cartConfig.i18n.selectCity;
                city.appendChild(placeholder);
                data.cities.forEach(function (cityRow) {
                    const option = document.createElement('option');
                    option.value = cityRow.id;
                    option.textContent = cityRow.label;
                    option.setAttribute('data-fee', String(cityRow.delivery_fee));
                    deliveryFeesById[String(cityRow.id)] = parseFloat(cityRow.delivery_fee);
                    if (String(previous) === String(cityRow.id)) {
                        option.selected = true;
                    }
                    city.appendChild(option);
                });
                city.setAttribute('data-fees', JSON.stringify(deliveryFeesById));
                applyDeliveryCitySelection();
            })
            .catch(function () {
                city.style.display = 'none';
                city.required = false;
            });
    }

    function syncCartAddress() {
        const select = document.getElementById('selected_address');
        const hiddenAddress = document.getElementById('address');
        const newAddressInput = document.getElementById('address_new');
        const newAddrControls = document.getElementById('new_address_controls');
        if (!select || !hiddenAddress) {
            return;
        }

        if (select.value === '__new__') {
            hiddenAddress.value = newAddressInput ? (newAddressInput.value.trim() || '') : '';
            if (newAddressInput) {
                newAddressInput.style.display = 'block';
            }
            if (newAddrControls) {
                newAddrControls.style.display = 'block';
            }
        } else {
            hiddenAddress.value = select.value || '';
            if (newAddressInput) {
                newAddressInput.style.display = 'none';
                newAddressInput.value = '';
            }
            if (newAddrControls) {
                newAddrControls.style.display = 'none';
            }
        }
    }

    function boot() {
        const { city, sharedInput, sharedHidden } = els();

        document.getElementById('selected_address')?.addEventListener('change', syncCartAddress);
        document.getElementById('address_new')?.addEventListener('input', function () {
            const hiddenAddress = document.getElementById('address');
            const select = document.getElementById('selected_address');
            if (hiddenAddress && select && select.value === '__new__') {
                hiddenAddress.value = this.value.trim();
            }
        });
        syncCartAddress();
        rebuildDeliveryFeesMap();
        city?.addEventListener('change', applyDeliveryCitySelection);
        city?.addEventListener('input', applyDeliveryCitySelection);
        applyDeliveryCitySelection();

        document.getElementById('shared-shipping-check-btn')?.addEventListener('click', function (event) {
            event.preventDefault();
            checkSharedShippingOrder();
        });
        document.getElementById('shared-shipping-clear-btn')?.addEventListener('click', function (event) {
            event.preventDefault();
            if (sharedInput) {
                sharedInput.value = '';
            }
            clearSharedShipping(true);
        });

        sharedInput?.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 6);
        });

        sharedInput?.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                checkSharedShippingOrder();
            }
        });

        if (sharedHidden && sharedHidden.value) {
            if (sharedInput && ! sharedInput.value.trim()) {
                sharedInput.value = sharedHidden.value;
            }
        }

        if (sharedInput && sharedInput.value.trim()) {
            checkSharedShippingOrder();
        }

        document.addEventListener('elx-country-code-changed', function (event) {
            if (sharedShippingActive) {
                clearSharedShipping(true);
            }
            loadDeliveryCities((event.detail && event.detail.code) || '+966');
        });

        const checkoutForm = document.querySelector('form[action="' + cartConfig.checkoutUrl + '"]');
        checkoutForm?.addEventListener('submit', function (e) {
            syncCartAddress();
            if (city && sharedShippingActive) {
                city.disabled = false;
            }
            if (cartConfig.hasUnavailableItems) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: cartConfig.i18n.errorTitle, text: cartConfig.i18n.unavailable });
                }

                return;
            }

            const hidden = document.getElementById('user_code_hidden');
            const codeSelect = document.getElementById('code_select');
            const customInput = document.getElementById('code_custom_input');
            const codeValue = ((hidden && hidden.value) || '').trim();
            if (!codeValue || (codeSelect && codeSelect.value === '__other__' && !(((customInput && customInput.value) || '').trim()))) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: cartConfig.i18n.errorTitle, text: cartConfig.i18n.codeRequired });
                }
                if (codeSelect && codeSelect.value === '__other__' && customInput) {
                    customInput.focus();
                }

                return;
            }

            if (!sharedShippingActive && city && city.required && !city.value) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: cartConfig.i18n.errorTitle, text: cartConfig.i18n.cityRequired });
                }
                city.focus();

                return;
            }

            const { sharedInput } = els();
            const rawSharedCode = (sharedInput ? sharedInput.value : '').trim();
            if (rawSharedCode.length > 0 && !sharedShippingActive) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: cartConfig.i18n.sharedUnverifiedTitle || cartConfig.i18n.errorTitle,
                        text: (cartConfig.i18n.sharedUnverifiedText || '').replace(':code', rawSharedCode),
                        showCancelButton: true,
                        confirmButtonText: cartConfig.i18n.sharedUnverifiedVerifyBtn || 'Verify',
                        cancelButtonText: cartConfig.i18n.sharedUnverifiedClearBtn || 'Clear',
                        confirmButtonColor: '#1f8db5',
                        cancelButtonColor: '#6c757d',
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            checkSharedShippingOrder();
                            const checkBtn = document.getElementById('shared-shipping-check-btn');
                            if (checkBtn) {
                                checkBtn.focus();
                            }
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            if (sharedInput) {
                                sharedInput.value = '';
                            }
                            clearSharedShipping(true);
                        }
                    });
                }

                return;
            }
        });
    }

    function init() {
        const configEl = document.getElementById('cart-checkout-config');
        if (!configEl) {
            return;
        }

        try {
            cartConfig = JSON.parse(configEl.textContent || '{}');
        } catch (e) {
            console.error('Cart checkout config is invalid JSON.', e);

            return;
        }

        currentCurrency = cartConfig.currency || '';
        deliveryFeesById = Object.assign({}, cartConfig.fees || {});

        window.elxCartUpdateDeliveryFee = applyDeliveryCitySelection;
        window.elxCartCheckSharedShipping = checkSharedShippingOrder;
        window.elxCartClearSharedShipping = function () {
            const { sharedInput } = els();
            if (sharedInput) {
                sharedInput.value = '';
            }
            clearSharedShipping(true);
        };

        boot();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(window);
