import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React, { useCallback, useRef } from 'react'
import { GestureHandlerRootView } from 'react-native-gesture-handler'
import BottomSheet from '../components/BottomSheet'


const BottomSheetScreen = () => {

    const ref = useRef(null);

    const onPress = useCallback(() => {

        const isActive = ref?.current?.isActive();

        if(isActive){
            ref?.current?.scrollTo(0);
        }
        else{
            ref?.current?.scrollTo(-200);
        }

    },[]);

    return (
        <GestureHandlerRootView style={{flex : 1}}>
            <View style={styles.container}>
                <TouchableOpacity style={styles.button} onPress={onPress} />
                <BottomSheet ref={ref} >
                    <View style={{flex : 1, backgroundColor : 'orange'}} />
                </BottomSheet>
            </View>
        </GestureHandlerRootView>
        
    )
}

export default BottomSheetScreen

const styles = StyleSheet.create({
    container : {
        flex : 1,
        backgroundColor : '#111',
        alignItems : 'center',
        justifyContent : 'center'
    },
    button : {
        height : 50,
        aspectRatio : 1,
        borderRadius : 25,
        backgroundColor : 'white',
        opacity : 0.6
    }
});