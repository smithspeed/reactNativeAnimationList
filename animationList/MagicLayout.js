import React, { useCallback, useState } from 'react'
import { ScrollView, StyleSheet, Text, View, TouchableOpacity } from 'react-native'
import Animated, { FadeIn, FadeInDown, FadeInLeft, FadeOut, Layout } from 'react-native-reanimated';

const LIST_ITEM_COLOR = '#1798DE';


const MagicLayout = () => {

    const [items, setItems] = useState([]);

    const onAdd = useCallback(() => {

        setItems((currentItems) => {

            const nextItemId = (currentItems[currentItems.length-1]?.id ?? 0) + 1;

            return [...currentItems, {id : nextItemId}];
        });

    },[]);

    const onDelete = useCallback((itemId) => {

        setItems((currentItems) => {
            return currentItems.filter((item) => item.id !== itemId)
        })

    },[]);

    return (
        <View style={styles.container}>
            <TouchableOpacity style={styles.floatingButton} onPress={onAdd}>
                <Text style={{fontSize:40, color : 'white'}}>+</Text>
            </TouchableOpacity>
            <ScrollView
                style={{flex : 1,}} 
                //contentContainerStyle={{paddingVertical: 50}}
            >
                {
                    items.map((item) => {
                        return (
                            <Animated.View 
                                key={item.id} 
                                style={styles.listItem} 
                                entering={FadeIn}
                                exiting={FadeOut}
                                layout={Layout.delay(100)}
                                onTouchEnd={() => onDelete(item.id)}
                            />
                        )
                    })
                }
            </ScrollView>
        </View>
    )
}

export default MagicLayout

const styles = StyleSheet.create({
    container : {
        flex : 1,
        backgroundColor : '#fff',
    },
    listItem : {
        height : 100,
        backgroundColor : LIST_ITEM_COLOR,
        width : '90%',
        marginVertical : 10,
        borderRadius: 20,
        alignSelf : 'center',
        //Shadow on Android
        elevation : 5,
        //Shadow on iOS
        shadowColor : 'black',
        shadowOpacity : 1,
        shadowOffset : {width : 0, height : 10},
        shadowRadius : 10

    },
    floatingButton : {
        width : 80,
        aspectRatio : 1,
        backgroundColor : 'black',
        borderRadius: 40,
        position : 'absolute',
        bottom : 50,
        right : '5%',
        zIndex : 10,
        alignItems : 'center',
        justifyContent : 'center'
    }
});