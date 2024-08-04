import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:test_ta_1/core/config/constants.dart';
import 'package:test_ta_1/core/model/property.dart' hide Image;
import 'package:test_ta_1/ui_component/page/detail_page.dart';
import 'package:test_ta_1/ui_component/widget/card_component/sub_property_card.dart'; // Import the new file

class SearchPage extends StatefulWidget {
  @override
  _SearchPageState createState() => _SearchPageState();
}

class _SearchPageState extends State<SearchPage> {
  List<Datum> properties = [];
  TextEditingController searchController = TextEditingController();
  String currentQuery = '';
  bool isLoading = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Search Properties'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: TextField(
              controller: searchController,
              onChanged: (value) {
                currentQuery = value;
                searchProperties(value);
              },
              decoration: InputDecoration(
                labelText: 'Search by name or address',
                suffixIcon: IconButton(
                  icon: Icon(Icons.clear),
                  onPressed: () {
                    searchController.clear();
                    currentQuery = '';
                    searchProperties('');
                  },
                ),
              ),
            ),
          ),
          Expanded(
            child: isLoading
                ? const Center(
                    child: CircularProgressIndicator(),
                  )
                : properties.isEmpty && currentQuery.isNotEmpty
                    ? const Center(
                        child: Text('No properties found'),
                      )
                    : ListView.builder(
                        itemCount: properties.length,
                        itemBuilder: (context, index) {
                          return GestureDetector(
                            onTap: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) =>
                                      DetailPage(property: properties[index]),
                                ),
                              );
                            },
                            child: SubPropertyCard(property: properties[index]),
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }

  Future<void> searchProperties(String query) async {
    if (query.isEmpty) {
      setState(() {
        properties = [];
      });
      return;
    }

    setState(() {
      isLoading = true;
    });

    Uri url = Uri.parse("$baseUrll/api/property/?search=$query");
    var res = await http.get(url);

    try {
      if (res.statusCode == 200) {
        var data = propertyFromJson(res.body);

        setState(() {
          if (query == currentQuery) {
            properties = data.data
                .where((property) =>
                    property.name.toLowerCase().contains(query.toLowerCase()) ||
                    property.address.toLowerCase().contains(query.toLowerCase()))
                .toList();
          } else {
            properties = [];
          }
          isLoading = false;
        });
      } else {
        print("Error Occurred");
        setState(() {
          properties = [];
          isLoading = false;
        });
      }
    } catch (e) {
      print("Error: $e");
      setState(() {
        properties = [];
        isLoading = false;
      });
    }
  }
}
