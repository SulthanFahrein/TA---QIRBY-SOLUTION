import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:intl/intl.dart';

import 'package:test_ta_1/core/service/c_property.dart';
import 'package:test_ta_1/ui_component/page/filter_succes_page.dart';
import 'package:test_ta_1/ui_component/widget/button_component/default_button.dart';
import 'package:test_ta_1/core/model/property.dart' as PropertyModel;
import 'package:test_ta_1/ui_component/widget/field_component/custom_text_form_field.dart';
import 'package:test_ta_1/ui_component/widget/field_component/dropdown_filter_field.dart';

class FilterPage extends StatefulWidget {
  FilterPage({Key? key}) : super(key: key);

  @override
  _FilterPageState createState() => _FilterPageState();
}

class _FilterPageState extends State<FilterPage> {
  late Future<List<PropertyModel.Datum>> _propertiesFuture;
  final TextEditingController minPriceController = TextEditingController();
  final TextEditingController maxPriceController = TextEditingController();
  int selectedBedroom = 0;
  int selectedBathroom = 0;
  int selectedGarage = 0;
  int selectedFloor = 0;
  final List<int> filterOptions = [0, 1, 2, 3, 4, 5];

  @override
  void initState() {
    super.initState();
    _propertiesFuture = fetchProperties();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.transparent,
        foregroundColor: Colors.black,
        title: const Text(
          "Filter",
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        centerTitle: true,
      ),
      body: FutureBuilder<List<PropertyModel.Datum>>(
        future: _propertiesFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          } else {
            return ListView(
              children: [
                const SizedBox(height: 24),
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Price',
                        style: Theme.of(context).textTheme.bodyMedium!.copyWith(
                              fontWeight: FontWeight.bold,
                            ),
                      ),
                      const Spacer(),
                      DefaultButton(
                        label: 'Reset',
                        hasShadow: false,
                        onTap: resetFields,
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 15),
                minmaxprice(),
                const SizedBox(height: 24),
                DropdownFilter(
                  label: 'Bedroom',
                  selectedValue: selectedBedroom,
                  options: filterOptions,
                  onChanged: (newValue) {
                    setState(() {
                      selectedBedroom = newValue ?? 0;
                    });
                  },
                ),
                const SizedBox(height: 24),
                DropdownFilter(
                  label: 'Bathroom',
                  selectedValue: selectedBathroom,
                  options: filterOptions,
                  onChanged: (newValue) {
                    setState(() {
                      selectedBathroom = newValue ?? 0;
                    });
                  },
                ),
                const SizedBox(height: 24),
                DropdownFilter(
                  label: 'Garage',
                  selectedValue: selectedGarage,
                  options: filterOptions,
                  onChanged: (newValue) {
                    setState(() {
                      selectedGarage = newValue ?? 0;
                    });
                  },
                ),
                const SizedBox(height: 24),
                DropdownFilter(
                  label: 'Number of Floors',
                  selectedValue: selectedFloor,
                  options: filterOptions,
                  onChanged: (newValue) {
                    setState(() {
                      selectedFloor = newValue ?? 0;
                    });
                  },
                ),
                const SizedBox(height: 24),
              ],
            );
          }
        },
      ),
      bottomNavigationBar: buildBottomNavigationBar(context),
    );
  }

  Padding minmaxprice() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        children: [
          Expanded(
            child: CustomTextFormField(
              controller: minPriceController,
              hintText: 'Min',
              keyboardType: TextInputType.number,
              inputFormatters: [
                FilteringTextInputFormatter.digitsOnly,
                ThousandsFormatter(),
              ],
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: CustomTextFormField(
              controller: maxPriceController,
              hintText: 'Max',
              keyboardType: TextInputType.number,
              inputFormatters: [
                FilteringTextInputFormatter.digitsOnly,
                ThousandsFormatter(),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget buildBottomNavigationBar(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border(
          top: BorderSide(color: Colors.grey[100]!, width: 1.5),
        ),
      ),
      height: 80,
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
      child: DefaultButton(
        label: 'Save',
        isExpand: true,
        onTap: () {
          filterProperties();
        },
      ),
    );
  }

  void resetFields() {
    setState(() {
      selectedBedroom = 0;
      selectedBathroom = 0;
      selectedGarage = 0;
      selectedFloor = 0;
      minPriceController.clear();
      maxPriceController.clear();
    });
  }

  void filterProperties() {
    fetchProperties().then((properties) {
      List<PropertyModel.Datum> filteredProperties =
          properties.where((property) {
        bool matchesBedroom =
            selectedBedroom == 0 || property.bed == selectedBedroom;
        bool matchesBathroom =
            selectedBathroom == 0 || property.bath == selectedBathroom;
        bool matchesGarage =
            selectedGarage == 0 || property.garage == selectedGarage;
        bool matchesFloor =
            selectedFloor == 0 || property.floor == selectedFloor;
        bool matchesPrice = (minPriceController.text.isEmpty ||
                int.parse(property.price.replaceAll('.', '')) >=
                    int.parse(minPriceController.text.replaceAll('.', ''))) &&
            (maxPriceController.text.isEmpty ||
                int.parse(property.price.replaceAll('.', '')) <=
                    int.parse(maxPriceController.text.replaceAll('.', '')));

        return matchesBedroom &&
            matchesBathroom &&
            matchesGarage &&
            matchesFloor &&
            matchesPrice;
      }).toList();

      if (filteredProperties.isEmpty) {
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text('Data not found'),
              actions: <Widget>[
                TextButton(
                  onPressed: () {
                    Navigator.of(context).pop();
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      } else {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => FilterSuccess(
              filteredProperties: filteredProperties,
              minPrice: minPriceController.text,
              maxPrice: maxPriceController.text,
              selectedBedroom: selectedBedroom,
              selectedBathroom: selectedBathroom,
              selectedGarage: selectedGarage,
            ),
          ),
        );
      }
    });
  }
}

class ThousandsFormatter extends TextInputFormatter {
  @override
  TextEditingValue formatEditUpdate(
      TextEditingValue oldValue, TextEditingValue newValue) {
    if (newValue.text.isEmpty) {
      return newValue.copyWith(text: '');
    }

    // Remove all existing dots to parse the number
    String value = newValue.text.replaceAll('.', '');

    // Parse the integer value
    int? parsedValue = int.tryParse(value);

    // If the value is null, keep the old value
    if (parsedValue == null) {
      return oldValue;
    }

    // Format the number with commas and then replace commas with dots
    final formatter = NumberFormat("#,###", "en_US");
    String formattedValue = formatter.format(parsedValue).replaceAll(',', '.');

    // Return the new text with the updated format and cursor position
    return newValue.copyWith(
      text: formattedValue,
      selection: TextSelection.collapsed(offset: formattedValue.length),
    );
  }
}
