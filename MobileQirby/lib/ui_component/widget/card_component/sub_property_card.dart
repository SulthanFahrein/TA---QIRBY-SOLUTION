import 'package:flutter/material.dart';
import 'package:test_ta_1/core/config/constants.dart';
import 'package:test_ta_1/core/model/property.dart' as PropertyModel;

class SubPropertyCard extends StatelessWidget {
  final PropertyModel.Datum property;
  
  const SubPropertyCard({Key? key, required this.property}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: SizedBox(
          width: 90,
          height: 60,
          child: property.images.isNotEmpty
              ? Image.network(
                  '$baseUrll/storage/images_property/${property.images[0].image}',
                  fit: BoxFit.cover,
                )
              : Container(),
        ),
        title: Padding(
          padding: const EdgeInsets.only(bottom: 8.0),
          child: Text(
            property.name,
            style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                  fontWeight: FontWeight.bold,
                ),
          ),
        ),
        subtitle: Text(
          property.address,
          style: Theme.of(context).textTheme.bodySmall!.copyWith(
                fontWeight: FontWeight.normal,
              ),
        ),
      ),
    );
  }
}
