var CustomersService = {
    getAllCustomers: function() {
        return fetch('/customers')
            .then(response => response.json());
    },
    getCustomerMeals: function(customerId) {
        return fetch(`/customer/meals/${customerId}`)
            .then(response => response.json());
    },
    addCustomer: function(data) {
        return fetch('/customers/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(response => response.json());
    }
};

document.addEventListener('DOMContentLoaded', function() {
    var customersList = document.getElementById('customers-list');
    var customerMealsTable = document.getElementById('customer-meals').querySelector('tbody');
    var addCustomerModal = document.getElementById('add-customer-modal');
    var addCustomerForm = addCustomerModal.querySelector('form');

    function populateCustomers() {
        CustomersService.getAllCustomers().then(customers => {
            customersList.innerHTML = '<option selected>Please select one customer</option>';
            customers.forEach(customer => {
                var option = document.createElement('option');
                option.value = customer.id;
                option.textContent = `${customer.first_name} ${customer.last_name}`;
                customersList.appendChild(option);
            });
        });
    }

    function populateCustomerMeals(customerId) {
        CustomersService.getCustomerMeals(customerId).then(meals => {
            customerMealsTable.innerHTML = '';
            meals.forEach(meal => {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <td>${meal.food_name}</td>
                    <td>${meal.food_brand}</td>
                    <td>${meal.meal_date}</td>
                `;
                customerMealsTable.appendChild(row);
            });
        });
    }

    customersList.addEventListener('change', function() {
        var customerId = this.value;
        if (customerId) {
            populateCustomerMeals(customerId);
        }
    });

    addCustomerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var data = {
            first_name: formData.get('first_name'),
            last_name: formData.get('last_name'),
            birth_date: formData.get('birth_date')
        };
        CustomersService.addCustomer(data).then(() => {
            populateCustomers();
            var modal = bootstrap.Modal.getInstance(addCustomerModal);
            modal.hide();
        });
    });

    populateCustomers();
});
